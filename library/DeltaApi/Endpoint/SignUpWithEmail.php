<?php

namespace DeltaApi\Endpoint;

use DeltaApi\EmailAddress;
use Model\AuthorizedEmailDomains;
use Model\SignUpRequests;
use Model\Users;
use Silex\Application as Silex;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SignUpWithEmail implements EndpointInterface
{
    const SUCCESS = 'success';

    const MUST_BE_POST = 'must-be-post';

    const EMAIL_ADDRESS_REQUIRED = 'email-address-required';

    const INVALID_EMAIL_ADDRESS = 'invalid-email-address';

    const UNAUTHORIZED_EMAIL_DOMAIN = 'unauthorized-email-domain';

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
     * @var AuthorizedEmailDomains
     */
    private $authorizedEmailDomainsModel;

    /**
     * @var SignUpRequests
     */
    private $signUpRequestsModel;

    public function __construct(
        Silex $silex,
        Users $usersModel,
        SignUpRequests $signUpRequestsModel,
        AuthorizedEmailDomains $authorizedEmailDomainsModel
    ) {
        $this->silex = $silex;

        $this->usersModel                  = $usersModel;
        $this->authorizedEmailDomainsModel = $authorizedEmailDomainsModel;
        $this->signUpRequestsModel         = $signUpRequestsModel;
    }

    public function respond(Request $request)
    {
        $emailAddress = new EmailAddress($request->request->get('email_address'));

        if ($request->getMethod() !== Request::METHOD_POST) {
            $response = $this->silex->json(
                [
                    'code'    => self::MUST_BE_POST,
                    'message' => 'Sign-up requests must be submitted with POST.'
                ],
                Response::HTTP_METHOD_NOT_ALLOWED
            );
        } else if (!$emailAddress->getAddress()) {
            $response = $this->silex->json(
                [
                    'code'    => self::EMAIL_ADDRESS_REQUIRED,
                    'message' => 'An email address is required to sign up.'
                ],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        } else if (!$emailAddress->isValid()) {
            $response = $this->silex->json(
                [
                    'message' => "{$emailAddress->getAddress()} is an invalid email address.",
                    'code'    => self::INVALID_EMAIL_ADDRESS
                ],
                Response::HTTP_BAD_REQUEST
            );
        } else if (!$this->authorizedEmailDomainsModel->isAuthorizedEmailAddress($emailAddress)) {
            $response = $this->silex->json(
                [
                    'message' => $this->authorizedEmailDomainsModel->getInvalidAddressErrorMessage(),
                    'code'    => self::UNAUTHORIZED_EMAIL_DOMAIN
                ],
                Response::HTTP_BAD_REQUEST
            );
        } else if ($this->usersModel->accountExistsWithEmailAddress($emailAddress)) {
            $response = $this->silex->json(
                [
                    'message' => 'An account already exists with that email address.',
                    'code'    => self::ACCOUNT_ALREADY_EXISTS
                ],
                Response::HTTP_BAD_REQUEST
            );
        } else {
            $response = $this->silex->json(
                [
                    'message' => 'Sign-up email sent.  Please check your email to get the verification code.',
                    'code'    => self::SUCCESS
                ]
            );

            $this->signUpRequestsModel->createForAuthorizedDomain($emailAddress, $this->authorizedEmailDomainsModel);
        }

        return $response;
    }
}
