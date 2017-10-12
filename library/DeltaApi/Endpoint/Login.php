<?php

namespace DeltaApi\Endpoint;

use DeltaApi\EmailAddress;
use Model\Users;
use Silex\Application as Silex;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Login implements EndpointInterface
{
    const SUCCESS = 'success';

    const MUST_BE_POST = 'must-be-post';

    const EMAIL_ADDRESS_REQUIRED = 'email-address-required';

    const PASSWORD_REQUIRED = 'password-required';

    const ACCOUNT_NOT_FOUND = 'account-not-found';

    const AUTHENTICATION_FAILED = 'authentication-failed';

    /**
     * @var Silex
     */
    private $silex;

    /**
     * @var Users
     */
    private $usersModel;

    public function __construct(Silex $silex, Users $usersModel)
    {
        $this->silex      = $silex;
        $this->usersModel = $usersModel;
    }

    public function respond(Request $request)
    {
        $emailAddress = new EmailAddress($request->request->get('email_address'));
        $password     = trim($request->request->get('password'));

        if (!$request->isMethod(Request::METHOD_POST)) {
            $response = $this->silex->json(
                [
                    'message' => 'Logins must be submitted via POST.',
                    'code'    => self::MUST_BE_POST
                ],
                Response::HTTP_METHOD_NOT_ALLOWED
            );
        } else if (!$emailAddress->getAddress()) {
            $response = $this->silex->json(
                [
                    'message' => 'Email address is required.',
                    'code'    => self::EMAIL_ADDRESS_REQUIRED
                ],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        } else if (!$password) {
            $response = $this->silex->json(
                [
                    'message' => 'Password is required.',
                    'code'    => self::PASSWORD_REQUIRED
                ],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        } else {
            $user = $this->usersModel->findByEmailAddress($emailAddress);

            if (!$user) {
                $response = $this->silex->json(
                    [
                        'message' => 'No account found matching that email address.',
                        'code'    => self::ACCOUNT_NOT_FOUND
                    ],
                    Response::HTTP_NOT_FOUND
                );
            } else if (!$this->usersModel->passwordIsCorrect($user->get('user_id'), $password)) {
                $response = $this->silex->json(
                    [
                        'message' => 'Authentication failed.  Please try again.',
                        'code'    => self::AUTHENTICATION_FAILED
                    ],
                    Response::HTTP_UNAUTHORIZED
                );
            } else {
                $response = $this->silex->json(
                    [
                        'message' => 'Successfully logged in.',
                        'code'    => self::SUCCESS,
                        'api_key' => $user->get('api_key')
                    ],
                    200
                );
            }
        }

        return $response;
    }
}
