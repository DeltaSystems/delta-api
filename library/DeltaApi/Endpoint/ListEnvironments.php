<?php

namespace DeltaApi\Endpoint;

use Dewdrop\Db\Row;
use Model\Environments;
use Model\Projects;
use Model\Users;
use Silex\Application as Silex;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ListEnvironments extends AbstractAuthenticatedEndpoint
{
    const SUCCESS = 'success';

    const MUST_BE_GET = 'must-be-get';

    const PROJECT_NOT_FOUND = 'not-found';

    /**
     * @var Projects
     */
    private $projectsModel;

    /**
     * @var Environments
     */
    private $environmentsModel;

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

        if (!$request->isMethod(Request::METHOD_GET)) {
            $response = $this->silex->json(
                [
                    'message' => 'Must be a GET request.',
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
        } else {
            $response = $this->silex->json(
                [
                    'message'      => 'Found project environments.',
                    'code'         => self::SUCCESS,
                    'environments' => $this->environmentsModel->getApiResponse($project['project_id'])
                ]
            );
        }

        return $response;
    }
}
