<?php

namespace DeltaApi\Endpoint;

use DeltaApi\EnvironmentProvider\EnvironmentProviderFactory;
use Dewdrop\Db\Row;
use Model\EnvironmentResourceType;
use Model\Environments;
use Model\Projects;
use Model\Users;
use Silex\Application as Silex;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CreateEnvironment extends AbstractAuthenticatedEndpoint
{
    const SUCCESS = 'success';

    const MUST_BE_POST = 'must-be-post';

    const PROJECT_NOT_FOUND = 'not-found';

    const PROVIDER_REQUIRED = 'provider-required';

    const NAME_REQUIRED = 'name-required';

    const PUBLIC_KEY_REQUIRED = 'public-key-required';

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
        $apiKey  = $request->attributes->get('apiKey');
        $project = $this->projectsModel->findByApiKey($apiKey);

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
        } else if (!$request->request->get('provider')) {
            $response = $this->silex->json(
                [
                    'message' => 'The environment provider is required.',
                    'code'    => self::PROVIDER_REQUIRED
                ],
                Response::HTTP_BAD_REQUEST
            );
        } else if (!$request->request->get('environment')) {
            $response = $this->silex->json(
                [
                    'message' => 'The environment name is required.',
                    'code'    => self::NAME_REQUIRED
                ],
                Response::HTTP_BAD_REQUEST
            );
        } else if (!$request->request->get('public_key')) {
            $response = $this->silex->json(
                [
                    'message' => 'The public key is required.',
                    'code'    => self::PUBLIC_KEY_REQUIRED
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
            $slug = $request->request->get('slug');
            $env  = $request->request->get('environment');

            $provider   = EnvironmentProviderFactory::createInstance($request->request->get('provider'));
            $username   = $provider->generateUsername($slug, $env);
            $password   = bin2hex(random_bytes(16));
            $domainName = $provider->generateDomainName($slug, $env);

            $this->runCurlOverSsh(
                '/create/user',
                [
                    'username' => $username,
                    'password' => $password
                ]
            );

            $this->runCurlOverSsh(
                '/create/vhost',
                [
                    'username'       => $username,
                    'domain'         => $domainName,
                    'applicationEnv' => $request->request->get('environment')
                ]
            );

            // Update account to use vhost as home folder
            $this->runCurlOverSsh(
                '/create/user',
                [
                    'username' => $username,
                    'home'     => "/var/www/vhosts/{$domainName}",
                    'password' => $password
                ]
            );

            $publicKey = openssl_pkey_get_public($request->request->get('public_key'));

            /* @var $environment \Model\Environments\Row */
            $environment = $this->environmentsModel->createRow();

            $environment
                ->set('project_id', $project->get('project_id'))
                ->set('name', $request->request->get('environment'))
                ->set('public_key_pem', $request->request->get('public_key'))
                ->set('created_by_user_id', $user->get('user_id'))
                ->save();

            $environment
                ->addResource(EnvironmentResourceType::BROWSER_URL, $domainName, $publicKey)
                ->addResource(EnvironmentResourceType::HOST, 'staging.servers.deltasys.com', $publicKey)
                ->addResource(EnvironmentResourceType::SFTP_USERNAME, $username, $publicKey)
                ->addResource(EnvironmentResourceType::SFTP_PASSWORD, $password, $publicKey)
                ->addResource(
                    EnvironmentResourceType::DEV_ENVIRONMENT_FLAG,
                    $provider->getIsDevEnvironment(),
                    $publicKey
                );

            $response = $this->silex->json(
                [
                    'message'         => 'Successfully created environment.',
                    'code'            => self::SUCCESS,
                    'environment'     => $environment->getApiResponse()
                ]
            );
        }

        return $response;
    }
}
