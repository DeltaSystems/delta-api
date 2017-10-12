<?php

namespace DeltaApi\Endpoint;

use DateTimeImmutable;
use Dewdrop\Db\Row;
use Model\Results;
use Model\Users;
use Silex\Application as Silex;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Log extends AbstractAuthenticatedEndpoint
{
    const SUCCESS = 'success';

    const MUST_BE_GET = 'must-be-get';

    const AFTER_PARAMETER_REQUIRED = 'after-param-required';

    /**
     * @var Results
     */
    private $resultsModel;

    public function __construct(Silex $silex, Results $resultsModel, Users $usersModel)
    {
        parent::__construct($silex, $usersModel);
        $this->resultsModel  = $resultsModel;
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
        } else if (!$request->query->get('after')) {
            $response = $this->silex->json(
                [
                    'message' => 'An "after" parameter is required in the format YYYY-mm-dd HH:MM:SS.',
                    'code'    => self::AFTER_PARAMETER_REQUIRED
                ],
                Response::HTTP_BAD_REQUEST
            );
        } else {
            $script      = $request->query->get('script');
            $environment = $request->query->get('environment');
            $after       = new DateTimeImmutable($request->query->get('after'));

            $entries = $this->resultsModel->fetchLog($script, $environment, $after);

            $response = $this->silex->json(
                [
                    'message' => 'Fetched log entries.',
                    'code'    => self::SUCCESS,
                    'entries' => $entries
                ]
            );
        }

        return $response;
    }
}
