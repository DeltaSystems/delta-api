<?php

namespace DeltaApi\Endpoint;

use Dewdrop\Db\Row;
use Model\Projects;
use Model\Users;
use Silex\Application as Silex;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GetEnvironment extends AbstractAuthenticatedEndpoint
{
    const SUCCESS = 'success';

    const MUST_BE_GET = 'must-be-get';

    const PROJECT_NOT_FOUND = 'project-not-found';

    const ENVIRONMENT_NOT_FOUND = 'environment-not-found';

    /**
     * @var Projects
     */
    private $projectsModel;

    public function __construct(Silex $silex, Projects $projectsModel, Users $usersModel)
    {
        parent::__construct($silex, $usersModel);
        $this->projectsModel = $projectsModel;
    }

    public function respondToAuthenticatedRequest(Request $request, Row $user)
    {
        $apiKey      = $request->attributes->get('apiKey');
        $project     = $this->projectsModel->findByApiKey($apiKey);
        $environment = $project->getEnvironment($request->attributes->get('environmentName'));

        if (!$request->isMethod(Request::METHOD_GET)) {
            $response = $this->silex->json(
                [
                    'message' => 'Must use GET.',
                    'code'    => self::MUST_BE_GET
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
            $response = $this->silex->json(
                [
                    'message' => 'Environment could not be found.',
                    'code'    => self::ENVIRONMENT_NOT_FOUND
                ],
                Response::HTTP_NOT_FOUND
            );
        } else {
            $response = $this->silex->json(
                [
                    'code'        => self::SUCCESS,
                    'message'     => 'Found environment.',
                    'environment' => $environment->getApiResponse()
                ]
            );
        }

        return $response;
    }
}
