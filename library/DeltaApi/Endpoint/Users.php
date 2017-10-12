<?php

namespace DeltaApi\Endpoint;

use Dewdrop\Db\Row;
use Model\Users as UsersModel;
use Silex\Application as Silex;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Users extends AbstractAuthenticatedEndpoint
{
    const SUCCESS = 'success';

    const MUST_BE_GET = 'must-be-get';

    public function __construct(Silex $silex, UsersModel $usersModel)
    {
        parent::__construct($silex, $usersModel);
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
            $users = $this->usersModel->fetchListing();

            $response = $this->silex->json(
                [
                    'message' => 'Fetched log entries.',
                    'code'    => self::SUCCESS,
                    'users'   => $users
                ]
            );
        }

        return $response;
    }
}
