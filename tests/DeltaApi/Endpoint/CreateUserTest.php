<?php

namespace DeltaApi\Endpoint;

use Model\SignUpRequests;
use Model\Users;
use PHPUnit_Framework_TestCase;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class CreateUserTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var CreateUser
     */
    private $endpoint;

    public function setUp()
    {
        $silex = new Application();

        $this->endpoint = new CreateUser($silex, new Users(), new SignUpRequests());
    }

    public function testNonPostRequestReturnsClientError()
    {
        $request = new Request();
        $request->setMethod(Request::METHOD_GET);
        $response = $this->endpoint->respond($request);
        $json     = json_decode($response->getContent(), true);

        $this->assertTrue($response->isClientError());
        $this->assertEquals(CreateUser::MUST_BE_POST, $json['code']);
        $this->assertArrayHasKey('message', $json);
    }

    public function testInvalidAuthorizationCodeReturnsClientError()
    {
        $request  = $this->createRequest('xxx', 'fafafafafafa', 'fafafafafafa');
        $response = $this->endpoint->respond($request);
        $json     = json_decode($response->getContent(), true);

        $this->assertTrue($response->isClientError());
        $this->assertEquals(CreateUser::INVALID_SIGN_UP_CODE, $json['code']);
        $this->assertArrayHasKey('message', $json);
    }

    public function testWeakPasswordReturnsClientError()
    {
        /* @var $signUpMock \Model\SignUpRequests|\PHPUnit_Framework_MockObject_MockObject */
        $signUpMock = $this->getMock(
            '\Model\SignUpRequests',
            ['findByAuthorizationCode']
        );

        $signUpMock->expects($this->any())
            ->method('findByAuthorizationCode')
            ->willReturn(
                $signUpMock->createRow(['authorization_code' => 'xxx', 'email_address' => 'no-account@deltasys.com'])
            );

        $endpoint = new CreateUser(new Application(), new Users(), $signUpMock);
        $request  = $this->createRequest('xxx', '1234', '1234');
        $response = $endpoint->respond($request);
        $json     = json_decode($response->getContent(), true);

        $this->assertTrue($response->isClientError());
        $this->assertEquals(CreateUser::PASSWORD_IS_WEAK, $json['code']);
        $this->assertArrayHasKey('message', $json);
    }

    public function testMissingPasswordReturnsClientError()
    {
        /* @var $signUpMock \Model\SignUpRequests|\PHPUnit_Framework_MockObject_MockObject */
        $signUpMock = $this->getMock(
            '\Model\SignUpRequests',
            ['findByAuthorizationCode']
        );

        $signUpMock->expects($this->any())
            ->method('findByAuthorizationCode')
            ->willReturn(
                $signUpMock->createRow(['authorization_code' => 'xxx', 'email_address' => 'no-account@deltasys.com'])
            );

        $endpoint = new CreateUser(new Application(), new Users(), $signUpMock);
        $request  = $this->createRequest('xxx', null, null);
        $response = $endpoint->respond($request);
        $json     = json_decode($response->getContent(), true);

        $this->assertTrue($response->isClientError());
        $this->assertEquals(CreateUser::PASSWORD_IS_REQUIRED, $json['code']);
        $this->assertArrayHasKey('message', $json);
    }

    public function testPasswordsThatDoNotMatchReturnClientError()
    {
        /* @var $signUpMock \Model\SignUpRequests|\PHPUnit_Framework_MockObject_MockObject */
        $signUpMock = $this->getMock(
            '\Model\SignUpRequests',
            ['findByAuthorizationCode']
        );

        $signUpMock->expects($this->any())
            ->method('findByAuthorizationCode')
            ->willReturn(
                $signUpMock->createRow(['authorization_code' => 'xxx', 'email_address' => 'no-account@deltasys.com'])
            );

        $endpoint = new CreateUser(new Application(), new Users(), $signUpMock);
        $request  = $this->createRequest('xxx', 'fafafafafafafafa', 'dadadadadadadada');
        $response = $endpoint->respond($request);
        $json     = json_decode($response->getContent(), true);

        $this->assertTrue($response->isClientError());
        $this->assertEquals(CreateUser::PASSWORDS_DO_NOT_MATCH, $json['code']);
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

        /* @var $signUpMock \Model\SignUpRequests|\PHPUnit_Framework_MockObject_MockObject */
        $signUpMock = $this->getMock(
            '\Model\SignUpRequests',
            ['findByAuthorizationCode']
        );

        $signUpMock->expects($this->any())
            ->method('findByAuthorizationCode')
            ->willReturn(
                $signUpMock->createRow(['authorization_code' => 'xxx', 'email_address' => 'bgriffith@deltasys.com'])
            );

        $endpoint = new CreateUser(new Application(), $usersMock, $signUpMock);
        $request  = $this->createRequest('xxx', 'fafafafafafa', 'fafafafafafa');
        $response = $endpoint->respond($request);
        $json     = json_decode($response->getContent(), true);

        $this->assertTrue($response->isClientError());
        $this->assertEquals(CreateUser::ACCOUNT_ALREADY_EXISTS, $json['code']);
        $this->assertArrayHasKey('message', $json);
    }

    public function testValidRequestResultsIn200ResponseWithApiKey()
    {
        /* @var $usersMock \Model\Users|\PHPUnit_Framework_MockObject_MockObject */
        $usersMock = $this->getMock(
            '\Model\Users',
            ['accountExistsWithEmailAddress', 'createAccount']
        );

        $usersMock->expects($this->any())
            ->method('accountExistsWithEmailAddress')
            ->willReturn(false);

        $usersMock->expects($this->once())
            ->method('createAccount')
            ->willReturn($usersMock->createRow(['email_address' => 'bgriffith@deltasys.com', 'api_key' => 'xxx']));

        /* @var $signUpMock \Model\SignUpRequests|\PHPUnit_Framework_MockObject_MockObject */
        $signUpMock = $this->getMock(
            '\Model\SignUpRequests',
            ['findByAuthorizationCode']
        );

        $signUpMock->expects($this->any())
            ->method('findByAuthorizationCode')
            ->willReturn(
                $signUpMock->createRow(['authorization_code' => 'xxx', 'email_address' => 'bgriffith@deltasys.com'])
            );

        $endpoint = new CreateUser(new Application(), $usersMock, $signUpMock);
        $request  = $this->createRequest('xxx', 'fafafafafafa', 'fafafafafafa');
        $response = $endpoint->respond($request);
        $json     = json_decode($response->getContent(), true);

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals(CreateUser::SUCCESS, $json['code']);
        $this->assertArrayHasKey('message', $json);
        $this->assertArrayHasKey('api_key', $json);
        $this->assertEquals('xxx', $json['api_key']);
    }

    private function createRequest($authorizationCode, $password, $confirmPassword)
    {
        $request = new Request();
        $request->setMethod(Request::METHOD_POST);
        $request->request->set('authorization_code', $authorizationCode);
        $request->request->set('password', $password);
        $request->request->set('confirm_password', $confirmPassword);
        return $request;
    }
}
