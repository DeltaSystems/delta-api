<?php

namespace DeltaApi\Endpoint;

use Dewdrop\Db\Row;
use Model\Projects;
use Model\Users;
use Silex\Application as Silex;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GetProject extends AbstractAuthenticatedEndpoint
{
    const SUCCESS = 'success';

    const MUST_BE_GET = 'must-be-get';

    const PROJECT_NOT_FOUND = 'not-found';

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
        $apiKey  = $request->attributes->get('apiKey');
        $project = $this->projectsModel->findByApiKey($apiKey);

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
        } else {
            $response = $this->silex->json(
                [
                    'message' => 'Found project.',
                    'code'    => self::SUCCESS,
                    'project' => $project->toArray()
                ]
            );
        }

        return $response;
    }
}
