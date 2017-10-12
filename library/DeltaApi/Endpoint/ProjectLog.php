<?php

namespace DeltaApi\Endpoint;

use Dewdrop\Db\Row;
use Model\Projects;
use Model\Results;
use Model\Users;
use Silex\Application as Silex;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProjectLog extends AbstractAuthenticatedEndpoint
{
    const SUCCESS = 'success';

    const MUST_BE_GET = 'must-be-get';

    const PROJECT_NOT_FOUND = 'not-found';

    /**
     * @var Results
     */
    private $resultsModel;

    /**
     * @var Projects
     */
    private $projectsModel;

    public function __construct(Silex $silex, Results $resultsModel, Projects $projectsModel, Users $usersModel)
    {
        parent::__construct($silex, $usersModel);
        $this->resultsModel  = $resultsModel;
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
            $script      = $request->query->get('script');
            $environment = $request->query->get('environment');

            $entries = $this->resultsModel->fetchProjectLog($project, $script, $environment);

            $response = $this->silex->json(
                [
                    'message' => 'Fetched project log entries.',
                    'code'    => self::SUCCESS,
                    'entries' => $entries
                ]
            );
        }

        return $response;
    }
}
