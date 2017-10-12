<?php

namespace Model;

use DeltaApi\EmailAddress;
use Dewdrop\Test\DbTestCase;

class SignUpRequestsTest extends DbTestCase
{
    /**
     * @var SignUpRequests
     */
    private $model;

    protected function getDataSet()
    {
        return $this->createFlatXMLDataSet(__DIR__ . '/datasets/sign-up-requests.xml');
    }

    public function setUp()
    {
        parent::setUp();

        $this->model = new SignUpRequests();
    }

    public function testCanCreateNewRequestForAuthorizedEmailAddress()
    {
        $mockTransport = $this->getMock('\Zend\Mail\Transport\TransportInterface');

        $mockTransport->expects($this->once())
            ->method('send');

        $emailAddress = new EmailAddress('bgriffith@deltasys.com');
        $this->model->createForAuthorizedDomain(
            $emailAddress,
            new AuthorizedEmailDomains(),
            $mockTransport
        );

        $request = $this->model->find(1);

        $this->assertEquals(1, $request->get('authorized_email_domain_id'));
        $this->assertEquals('bgriffith@deltasys.com', $request->get('email_address'));
    }

    public function testGenerateAuthorizationCodeReturnsLongRandomString()
    {
        $code = $this->model->generateAuthorizationCode();

        $this->assertTrue(16 <= strlen($code));

        $secondCode = $this->model->generateAuthorizationCode();
        $this->assertNotEquals($code, $secondCode);
    }

    public function testCanFindRequestByAuthorizationCode()
    {
        $mockTransport = $this->getMock('\Zend\Mail\Transport\TransportInterface');

        $emailAddress = new EmailAddress('bgriffith@deltasys.com');
        $this->model->createForAuthorizedDomain(
            $emailAddress,
            new AuthorizedEmailDomains(),
            $mockTransport
        );

        $createdRequest = $this->model->find(1);
        $requestByCode  = $this->model->findByAuthorizationCode($createdRequest->get('authorization_code'));

        $this->assertInstanceOf('\Dewdrop\Db\Row', $requestByCode);
        $this->assertEquals($createdRequest->get('authorization_code'), $requestByCode->get('authorization_code'));

        $this->assertNull($this->model->findByAuthorizationCode('xxxx'));
    }
}
