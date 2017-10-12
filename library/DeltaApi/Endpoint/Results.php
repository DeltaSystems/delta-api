<?php

namespace DeltaApi\Endpoint;

use DeltaApi\SlackNotification;
use Dewdrop\Db\Row;
use Model\Projects;
use Model\Results as ResultsModel;
use Model\Results\Row as ResultsRow;
use Model\Users;
use Silex\Application as Silex;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Results extends AbstractAuthenticatedEndpoint
{
    const SUCCESS = 'success';

    const MUST_BE_POST = 'must-be-post';

    const PROJECT_NOT_FOUND = 'not-found';

    const COULD_NOT_PARSE_RESULTS_JSON = 'could-not-parse-results-json';

    const INVALID_RESULTS_JSON = 'invalid-results-json';

    const SLACK_FAILED = 'failed-to-send-slack-notification';

    /**
     * @var Results
     */
    private $resultsModel;

    /**
     * @var Projects
     */
    private $projectsModel;

    public function __construct(Silex $silex, ResultsModel $resultsModel, Projects $projectsModel, Users $usersModel)
    {
        parent::__construct($silex, $usersModel);

        $this->resultsModel  = $resultsModel;
        $this->projectsModel = $projectsModel;
    }

    public function respondToAuthenticatedRequest(Request $request, Row $user)
    {
        $project = $this->projectsModel->findByApiKey($request->request->get('project'));
        $results = @json_decode($request->request->get('results'), true);

        if (!$request->isMethod(Request::METHOD_POST)) {
            $response = $this->silex->json(
                [
                    'message' => 'Results must be sent via POST.',
                    'code'    => self::MUST_BE_POST
                ],
                Response::HTTP_METHOD_NOT_ALLOWED
            );
        } else if (!$project) {
            $response = $this->silex->json(
                [
                    'message' => 'Could not find project.',
                    'code'    => self::PROJECT_NOT_FOUND
                ],
                Response::HTTP_NOT_FOUND
            );
        } else if (!$results) {
            $response = $this->silex->json(
                [
                    'message' => 'Could not parse results JSON.',
                    'code'    => self::COULD_NOT_PARSE_RESULTS_JSON
                ],
                Response::HTTP_BAD_REQUEST
            );
        } else if (!$this->resultsModel->validateResults($results)) {
            $response = $this->silex->json(
                [
                    'message' => 'Results JSON was invalid.',
                    'code'    => self::INVALID_RESULTS_JSON
                ],
                Response::HTTP_BAD_REQUEST
            );
        } else {
            $results = $this->resultsModel->saveResults($results, $project, $user);

            if ((boolean) $request->request->get('send_notifications', 1)) {
                $response = $this->respondAfterSendingNotifications($request, $results);
            } else {
                $response = $this->silex->json(
                    [
                        'message' => 'Successfully logged result but skipped notifications.',
                        'code'    => self::SUCCESS
                    ]
                );
            }

        }

        return $response;
    }

    private function respondAfterSendingNotifications(Request $request, ResultsRow $results)
    {
        $handles = @json_decode($request->request->get('slack_handles'), true);

        if (!is_array($handles)) {
            $handles = [];
        }

        $slackNotification = new SlackNotification($results);
        $slackNotification
            ->setChannel($request->request->get('slack_channel'))
            ->setAtMessageHandles($handles);
        $slackResponse = $slackNotification->send();

        if (200 === $slackResponse->getStatusCode()) {
            $response = $this->silex->json(
                [
                    'message' => 'Successfully saved results and sent notifications.',
                    'code'    => self::SUCCESS
                ]
            );
        } else {
            $response = $this->silex->json(
                [
                    'message' => 'Failed to send Slack notifications.',
                    'code'    => self::SLACK_FAILED
                ],
                500
            );
        }

        return $response;
    }
}
