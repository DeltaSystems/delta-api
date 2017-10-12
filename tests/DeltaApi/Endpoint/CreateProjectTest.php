<?php

namespace DeltaApi\Endpoint;

use Dewdrop\Test\DbTestCase;
use Model\Projects;
use Model\Users;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class CreateProjectTest extends DbTestCase
{
    /**
     * @var CreateUser
     */
    private $endpoint;

    public function setUp()
    {
        parent::setUp();

        $silex = new Application();

        $this->endpoint = new CreateProject($silex, new Projects(), new Users());
    }

    protected function getDataSet()
    {
        return $this->createFlatXMLDataSet(__DIR__ . '/datasets/create-project.xml');
    }

    public function testNonPostRequestReturnsClientError()
    {
        $request = new Request();
        $request->setMethod(Request::METHOD_GET);
        $request->headers->set('php-auth-pw', 'valid-api-key');
        $response = $this->endpoint->respond($request);
        $json     = json_decode($response->getContent(), true);

        $this->assertTrue($response->isClientError());
        $this->assertEquals(CreateProject::MUST_BE_POST, $json['code']);
        $this->assertArrayHasKey('message', $json);
    }

    public function testInvalidApiKeyReturnsClientError()
    {
        $request = $this->createRequest('Test Project');
        $request->headers->set('php-auth-pw', 'this-is-not-a-valid-api-key');
        $response = $this->endpoint->respond($request);
        $json     = json_decode($response->getContent(), true);

        $this->assertTrue($response->isClientError());
        $this->assertEquals(CreateProject::AUTHENTICATION_FAILED, $json['code']);
        $this->assertArrayHasKey('message', $json);
    }

    public function testMissingNameParamReturnsClientError()
    {
        $request  = $this->createRequest('');
        $response = $this->endpoint->respond($request);
        $json     = json_decode($response->getContent(), true);

        $this->assertTrue($response->isClientError());
        $this->assertEquals(CreateProject::NAME_REQUIRED, $json['code']);
        $this->assertArrayHasKey('message', $json);
    }

    public function testSuccessfulCreationOfProjectIncludesApiKey()
    {
        $request  = $this->createRequest('New Project');
        $response = $this->endpoint->respond($request);
        $json     = json_decode($response->getContent(), true);

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals(CreateProject::SUCCESS, $json['code']);
        $this->assertArrayHasKey('message', $json);
        $this->assertArrayHasKey('api_key', $json);
    }

    private function createRequest($name)
    {
        $request = new Request();
        $request->headers->set('php-auth-pw', 'valid-api-key');
        $request->setMethod(Request::METHOD_POST);
        $request->request->set('name', $name);
        return $request;
    }
}
