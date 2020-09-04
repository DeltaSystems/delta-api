<?php

namespace Model;

use DeltaApi\EmailAddress;
use Dewdrop\Test\DbTestCase;

class AuthorizedEmailDomainsTest extends DbTestCase
{
    /**
     * @var AuthorizedEmailDomains
     */
    private $model;

    protected function getDataSet()
    {
        return $this->createFlatXMLDataSet(__DIR__ . '/datasets/authorized-email-domains.xml');
    }

    public function setUp()
    {
        parent::setUp();

        $this->model = new AuthorizedEmailDomains();
    }

    public function testCorrectlyDetectsUseOfAuthorizedDomain()
    {
        $authorized = new EmailAddress('bgriffith@deltasys.com');
        $this->assertTrue($this->model->isAuthorizedEmailAddress($authorized));

        $unauthorized = new EmailAddress('nope@unauthorized.com');
        $this->assertFalse($this->model->isAuthorizedEmailAddress($unauthorized));
    }

    public function testErrorMessageIncludesAllAuthorizedDomains()
    {
        $message = $this->model->getInvalidAddressErrorMessage();

        $this->assertContains('deltasys.com', $message);
        $this->assertContains('ventamarketing.com', $message);
    }

    public function testCanGetIdForDomainOfValidEmailAddress()
    {
        $this->assertEquals(1, $this->model->fetchIdForEmailAddress(new EmailAddress('bgriffith@deltasys.com')));
    }
}
