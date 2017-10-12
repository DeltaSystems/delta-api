<?php

namespace DeltaApi\Endpoint;

use Dewdrop\Db\Row;
use Model\Projects as ProjectsModel;
use Model\Users as UsersModel;
use Silex\Application as Silex;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Projects extends AbstractAuthenticatedEndpoint
{
    const SUCCESS = 'success';

    const MUST_BE_GET = 'must-be-get';

    /**
     * @var ProjectsModel
     */
    private $projectsModel;

    public function __construct(Silex $silex, ProjectsModel $projectsModel, UsersModel $usersModel)
    {
        parent::__construct($silex, $usersModel);
        $this->projectsModel = $projectsModel;
    }

    public function respondToAuthenticatedRequest(Request $request, Row $user)
    {
        if (!$request->isMethod(Request::METHOD_GET)) {
            $response = $this->silex->json(
                [
                    'message' => 'Must use GET.',
                    'code'    => self::MUST_BE_GET
                ],
                Response::HTTP_METHOD_NOT_ALLOWED
            );
        } else {
            $projects = $this->projectsModel->fetchListing();

            $response = $this->silex->json(
                [
                    'message'  => 'Fetched projects listing.',
                    'code'     => self::SUCCESS,
                    'projects' => $projects
                ]
            );
        }

        return $response;
    }
}
