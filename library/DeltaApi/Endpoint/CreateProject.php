<?php

namespace DeltaApi\Endpoint;

use Dewdrop\Db\Row;
use Model\Projects;
use Model\Users;
use Silex\Application as Silex;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CreateProject extends AbstractAuthenticatedEndpoint
{
    const SUCCESS = 'success';

    const MUST_BE_POST = 'must-be-post';

    const NAME_REQUIRED = 'name-is-required';

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
        $name = trim($request->request->get('name'));

        if (!$request->isMethod(Request::METHOD_POST)) {
            $response = $this->silex->json(
                [
                    'message' => 'Projects can only be created with a POST request.',
                    'code'    => self::MUST_BE_POST
                ],
                Response::HTTP_METHOD_NOT_ALLOWED
            );
        } else if (!$name) {
            $response = $this->silex->json(
                [
                    'message' => 'Project name is required.',
                    'code'    => self::NAME_REQUIRED
                ],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        } else {
            $project = $this->projectsModel->createProject($name, $user);

            $response = $this->silex->json(
                [
                    'message' => 'Successfully created project.',
                    'code'    => self::SUCCESS,
                    'api_key' => $project->get('api_key')
                ]
            );
        }

        return $response;
    }
}
