<?php

namespace DeltaApi\Endpoint;

use DeltaApi\EmailAddress;
use Model\AuthorizedEmailDomains;
use Model\SignUpRequests;
use Model\Users;
use PHPUnit_Framework_TestCase;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class SignUpWithEmailTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var SignUpWithEmail
     */
    private $endpoint;

    public function setUp()
    {
        $silex = new Application();

        $this->endpoint = new SignUpWithEmail($silex, new Users(), new SignUpRequests(), new AuthorizedEmailDomains());
    }

    public function testEmailAddressFromUnauthorizedDomainReturnsClientError()
    {
        $request = new Request();
        $request->setMethod(Request::METHOD_POST);
        $request->request->set('email_address', 'bgriffith@example.org');
        $response = $this->endpoint->respond($request);
        $json     = json_decode($response->getContent(), true);

        $this->assertTrue($response->isClientError());
        $this->assertEquals(SignUpWithEmail::UNAUTHORIZED_EMAIL_DOMAIN, $json['code']);
        $this->assertArrayHasKey('message', $json);
    }

    public function testNoEmailAddressReturnsClientError()
    {
        $request = new Request();
        $request->setMethod(Request::METHOD_POST);
        $response = $this->endpoint->respond($request);
        $json     = json_decode($response->getContent(), true);

        $this->assertTrue($response->isClientError());
        $this->assertEquals(SignUpWithEmail::EMAIL_ADDRESS_REQUIRED, $json['code']);
        $this->assertArrayHasKey('message', $json);
    }

    public function testInvalidEmailAddressReturnsClientError()
    {
        $request = new Request();
        $request->setMethod(Request::METHOD_POST);
        $request->request->set('email_address', 'bgriffith');
        $response = $this->endpoint->respond($request);
        $json     = json_decode($response->getContent(), true);

        $this->assertTrue($response->isClientError());
        $this->assertEquals(SignUpWithEmail::INVALID_EMAIL_ADDRESS, $json['code']);
        $this->assertArrayHasKey('message', $json);
    }

    public function testNonPostRequestReturnsClientError()
    {
        $request = new Request();
        $request->setMethod(Request::METHOD_GET);
        $response = $this->endpoint->respond($request);
        $json     = json_decode($response->getContent(), true);

        $this->assertTrue($response->isClientError());
        $this->assertEquals(SignUpWithEmail::MUST_BE_POST, $json['code']);
        $this->assertArrayHasKey('message', $json);
    }

    public function testUseOfEmailAddressTiedToExistingAccountReturnsClientError()
    {
        /* @var $usersMock \Model\Users|\PHPUnit_Framework_MockObject_MockObject */
        $usersMock = $this->getMock(
            '\Model\Users',
            ['accountExistsWithEmailAddress']
        );

        $usersMock->expects($this->any())
            ->method('accountExistsWithEmailAddress')
            ->willReturn(true);

        $endpoint = new SignUpWithEmail(
            new Application(),
            $usersMock,
            new SignUpRequests(),
            new AuthorizedEmailDomains()
        );

        $request = new Request();
        $request->setMethod(Request::METHOD_POST);
        $request->request->set('email_address', 'existing-account@deltasys.com');
        $response = $endpoint->respond($request);
        $json     = json_decode($response->getContent(), true);

        $this->assertTrue($response->isClientError());
        $this->assertEquals(SignUpWithEmail::ACCOUNT_ALREADY_EXISTS, $json['code']);
        $this->assertArrayHasKey('message', $json);
    }

    public function testSuccessfulSignUpResultsInCreationOfNewRequest()
    {
        /* @var $usersMock \Model\Users|\PHPUnit_Framework_MockObject_MockObject */
        $usersMock = $this->getMock(
            '\Model\Users',
            ['accountExistsWithEmailAddress']
        );

        $usersMock->expects($this->any())
            ->method('accountExistsWithEmailAddress')
            ->willReturn(false);

        /* @var $signUpMock \Model\SignUpRequests|\PHPUnit_Framework_MockObject_MockObject */
        $signUpMock = $this->getMock(
            '\Model\SignUpRequests',
            ['createForAuthorizedDomain']
        );

        $authorizedDomainModel = new AuthorizedEmailDomains();

        $signUpMock->expects($this->once())
            ->method('createForAuthorizedDomain')
            ->with(
                new EmailAddress('valid-address@deltasys.com'),
                $authorizedDomainModel
            );

        $endpoint = new SignUpWithEmail(
            new Application(),
            $usersMock,
            $signUpMock,
            $authorizedDomainModel
        );

        $request = new Request();
        $request->setMethod(Request::METHOD_POST);
        $request->request->set('email_address', 'valid-address@deltasys.com');
        $response = $endpoint->respond($request);
        $json     = json_decode($response->getContent(), true);

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals(SignUpWithEmail::SUCCESS, $json['code']);
        $this->assertArrayHasKey('message', $json);
    }
}
