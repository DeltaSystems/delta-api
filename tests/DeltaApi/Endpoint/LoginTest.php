<?php

namespace DeltaApi\Endpoint;

use Model\Users;
use PHPUnit_Framework_TestCase;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class LoginTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var CreateUser
     */
    private $endpoint;

    public function setUp()
    {
        $silex = new Application();

        $this->endpoint = new Login($silex, new Users());
    }

    public function testNonPostRequestReturnsClientError()
    {
        $request = new Request();
        $request->setMethod(Request::METHOD_GET);
        $response = $this->endpoint->respond($request);
        $json     = json_decode($response->getContent(), true);

        $this->assertTrue($response->isClientError());
        $this->assertEquals(Login::MUST_BE_POST, $json['code']);
        $this->assertArrayHasKey('message', $json);
    }

    public function testMissingEmailAddressReturnsClientError()
    {
        $response = $this->endpoint->respond($this->createRequest('', 'password'));
        $json     = json_decode($response->getContent(), true);

        $this->assertTrue($response->isClientError());
        $this->assertEquals(Login::EMAIL_ADDRESS_REQUIRED, $json['code']);
        $this->assertArrayHasKey('message', $json);
    }

    public function testMissingEmailPasswordReturnsClientError()
    {
        $response = $this->endpoint->respond($this->createRequest('email@example.org', ''));
        $json     = json_decode($response->getContent(), true);

        $this->assertTrue($response->isClientError());
        $this->assertEquals(Login::PASSWORD_REQUIRED, $json['code']);
        $this->assertArrayHasKey('message', $json);
    }

    public function testUnknownEmailAddressReturnsClientError()
    {
        $response = $this->endpoint->respond($this->createRequest('email@example.org', 'password'));
        $json     = json_decode($response->getContent(), true);

        $this->assertTrue($response->isClientError());
        $this->assertEquals(Login::ACCOUNT_NOT_FOUND, $json['code']);
        $this->assertArrayHasKey('message', $json);
    }

    public function testFailedAuthenticationReturnsClientError()
    {
        /* @var $usersMock Users|\PHPUnit_Framework_MockObject_MockObject */
        $usersMock = $this->getMock(
            '\Model\Users',
            ['passwordIsCorrect', 'findByEmailAddress']
        );

        $usersMock->expects($this->once())
            ->method('findByEmailAddress')
            ->willReturn($usersMock->createRow(['email_address' => 'email@example.org']));

        $usersMock->expects($this->once())
            ->method('passwordIsCorrect')
            ->willReturn(false);

        $endpoint = new Login(new Application(), $usersMock);
        $response = $endpoint->respond($this->createRequest('email@example.org', 'password'));
        $json     = json_decode($response->getContent(), true);

        $this->assertTrue($response->isClientError());
        $this->assertEquals(Login::AUTHENTICATION_FAILED, $json['code']);
        $this->assertArrayHasKey('message', $json);
    }

    public function testSuccessfulResponseIncludesApiKey()
    {
        /* @var $usersMock Users|\PHPUnit_Framework_MockObject_MockObject */
        $usersMock = $this->getMock(
            '\Model\Users',
            ['passwordIsCorrect', 'findByEmailAddress']
        );

        $usersMock->expects($this->once())
            ->method('findByEmailAddress')
            ->willReturn($usersMock->createRow(['email_address' => 'email@example.org', 'api_key' => 'xxx']));

        $usersMock->expects($this->once())
            ->method('passwordIsCorrect')
            ->willReturn(true);

        $endpoint = new Login(new Application(), $usersMock);
        $response = $endpoint->respond($this->createRequest('email@example.org', 'password'));
        $json     = json_decode($response->getContent(), true);

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals(Login::SUCCESS, $json['code']);
        $this->assertArrayHasKey('message', $json);
        $this->assertArrayHasKey('api_key', $json);
        $this->assertEquals('xxx', $json['api_key']);
    }

    private function createRequest($emailAddress, $password)
    {
        $request = new Request();
        $request->setMethod(Request::METHOD_POST);
        $request->request->set('email_address', $emailAddress);
        $request->request->set('password', $password);
        return $request;
    }
}
