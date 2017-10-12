<?php

namespace DeltaApi\Endpoint;

use Dewdrop\Db\Row;
use Model\EnvironmentResourceType;
use Model\Environments;
use Model\Projects;
use Model\Users;
use Silex\Application as Silex;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CreateDatabase extends AbstractAuthenticatedEndpoint
{
    const SUCCESS = 'success';

    const MUST_BE_POST = 'must-be-post';

    const PROJECT_NOT_FOUND = 'project-not-found';

    const ENVIRONMENT_NOT_FOUND = 'environment-not-found';

    const TYPE_REQUIRED = 'type-required';

    const SLUG_REQUIRED = 'slug-required';

    /**
     * @var Projects
     */
    private $projectsModel;

    /**
     * @var Environments
     */
    private $environmentsModel;

    /**
     * CreateEnvironment constructor.
     * @param Silex $silex
     * @param Projects $projectsModel
     * @param Environments $environmentsModel
     * @param Users $usersModel
     */
    public function __construct(
        Silex $silex,
        Projects $projectsModel,
        Environments $environmentsModel,
        Users $usersModel
    ) {
        parent::__construct($silex, $usersModel);
        $this->projectsModel     = $projectsModel;
        $this->environmentsModel = $environmentsModel;
    }

    public function respondToAuthenticatedRequest(Request $request, Row $user)
    {
        $apiKey      = $request->attributes->get('apiKey');
        $project     = $this->projectsModel->findByApiKey($apiKey);
        $environment = $project->getEnvironment($request->attributes->get('environmentName'));

        if (!$request->isMethod(Request::METHOD_POST)) {
            $response = $this->silex->json(
                [
                    'message' => 'Must be a POST request.',
                    'code'    => self::MUST_BE_POST
                ],
                Response::HTTP_METHOD_NOT_ALLOWED
            );
        } else if (!$project) {
            $response = $this->silex->json(
                [
                    'message' => 'Project could not be found.',
                    'code'    => self::PROJECT_NOT_FOUND
                ],
                Response::HTTP_NOT_FOUND
            );
        } else if (!$environment) {
            $environmentName = $request->attributes->get('environmentName');

            $response = $this->silex->json(
                [
                    'message' => "An environment named {$environmentName} could not be found.",
                    'code'    => self::ENVIRONMENT_NOT_FOUND
                ],
                Response::HTTP_BAD_REQUEST
            );
        } elseif (!$this->isValidType($request->request->get('type'))) {
            $response = $this->silex->json(
                [
                    'message' => 'A database type of either "postgres" or "mysql" is required.',
                    'code'    => self::ENVIRONMENT_NOT_FOUND
                ],
                Response::HTTP_BAD_REQUEST
            );
        } else if (!$request->request->get('slug')) {
            $response = $this->silex->json(
                [
                    'message' => 'The slug is required.',
                    'code'    => self::SLUG_REQUIRED
                ],
                Response::HTTP_BAD_REQUEST
            );
        } else {
            $name     = sprintf('%s_%s_dev', $request->request->get('slug'), $environment->get('name'));
            $password = bin2hex(random_bytes(16));

            if ('mysql' === $request->request->get('type')) {
                $username = substr($name, 0, 16);
            } else {
                $username = $name;
            }

            $this->runCurlOverSsh(
                '/create/db',
                [
                    'type'     => $this->getTypeForCurl($request->request->get('type')),
                    'dbname'   => $name,
                    'password' => $password,
                    'username' => $username
                ]
            );

            $publicKey = openssl_pkey_get_public($environment->get('public_key_pem'));

            $environment->addResource(
                EnvironmentResourceType::DATABASE,
                [
                    'type'     => $request->request->get('type'),
                    'username' => $username,
                    'password' => $password,
                    'name'     => $name,
                    'host'     => 'localhost'
                ],
                $publicKey
            );

            $response = $this->silex->json(
                [
                    'message'     => 'Successfully created database.',
                    'code'        => self::SUCCESS,
                    'environment' => $environment->getApiResponse()
                ]
            );
        }

        return $response;
    }

    private function isValidType($type)
    {
        return $type && in_array($type, ['postgres', 'mysql']);
    }

    private function getTypeForCurl($type)
    {
        switch ($type) {
            case 'postgres':
                return 'pgsql';
            case 'mysql':
                return 'mysql';
        }
    }
}
