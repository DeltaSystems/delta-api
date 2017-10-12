<?php

namespace DeltaApi\Endpoint;

use DeltaApi\EmailAddress;
use Model\SignUpRequests;
use Model\Users;
use Silex\Application as Silex;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CreateUser implements EndpointInterface
{
    const SUCCESS = 'success';

    const MUST_BE_POST = 'must-be-post';

    const INVALID_SIGN_UP_CODE = 'invalid-sign-up-authorization-code';

    const PASSWORD_IS_REQUIRED = 'password-is-required';

    const PASSWORDS_DO_NOT_MATCH = 'passwords-do-not-match';

    const PASSWORD_IS_WEAK = 'password-is-weak';

    const ACCOUNT_ALREADY_EXISTS = 'account-already-exists';

    /**
     * @var Silex
     */
    private $silex;

    /**
     * @var Users
     */
    private $usersModel;

    /**
     * @var SignUpRequests
     */
    private $signUpRequestsModel;

    public function __construct(
        Silex $silex,
        Users $usersModel,
        SignUpRequests $signUpRequestsModel
    ) {
        $this->silex = $silex;

        $this->usersModel          = $usersModel;
        $this->signUpRequestsModel = $signUpRequestsModel;
    }

    public function respond(Request $request)
    {
        $authorizationCode = $request->request->get('authorization_code');
        $signUpRequest     = $this->signUpRequestsModel->findByAuthorizationCode($authorizationCode);
        $emailAddress      = null;
        $password          = trim($request->request->get('password'));
        $confirmPassword   = trim($request->request->get('confirm_password'));

        if ($signUpRequest) {
            $emailAddress = new EmailAddress($signUpRequest->get('email_address'));
        }

        if ($request->getMethod() !== Request::METHOD_POST) {
            $response = $this->silex->json(
                [
                    'code'    => self::MUST_BE_POST,
                    'message' => 'Sign-up requests must be submitted with POST.'
                ],
                Response::HTTP_METHOD_NOT_ALLOWED
            );
        } else if (!$signUpRequest) {
            $response = $this->silex->json(
                [
                    'code'     => self::INVALID_SIGN_UP_CODE,
                    'message' => 'Invalid sign-up authorization code submitted.',
                ],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        } else if ($this->usersModel->accountExistsWithEmailAddress($emailAddress)) {
            $response = $this->silex->json(
                [
                    'code' => self::ACCOUNT_ALREADY_EXISTS,
                    'message' => "A user account already exists with that the address {$emailAddress->getAddress()}.",
                ],
                Response::HTTP_BAD_REQUEST
            );
        } else if (!$password || !$confirmPassword) {
            $response = $this->silex->json(
                [
                    'code'    => self::PASSWORD_IS_REQUIRED,
                    'message' => '"password" and "confirm_password" fields are required.',
                ],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        } else if (!$this->usersModel->passwordsMatch($password, $confirmPassword)) {
            $response = $this->silex->json(
                [
                    'code'    => self::PASSWORDS_DO_NOT_MATCH,
                    'message' => '"password" and "confirm_password" do not match.',
                ],
                Response::HTTP_BAD_REQUEST
            );
        } else if (!$this->usersModel->passwordIsSuitablyComplex($password)) {
            $response = $this->silex->json(
                [
                    'code'    => self::PASSWORD_IS_WEAK,
                    'message' => 'Password is too weak.  Must be at least 12 characters long.',
                ],
                Response::HTTP_BAD_REQUEST
            );
        } else {
            $user = $this->usersModel->createAccount($emailAddress, $password);

            $response = $this->silex->json(
                [
                    'message' => 'Account created.',
                    'code'    => self::SUCCESS,
                    'api_key' => $user->get('api_key')
                ]
            );
        }

        return $response;
    }
}
