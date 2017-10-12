<?php

namespace DeltaApi\Endpoint;

use Dewdrop\Test\DbTestCase;
use Model\Projects;
use Model\Users;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class GetProjectTest extends DbTestCase
{
    /**
     * @var CreateUser
     */
    private $endpoint;

    public function setUp()
    {
        parent::setUp();

        $silex = new Application();

        $this->endpoint = new GetProject($silex, new Projects(), new Users());
    }

    protected function getDataSet()
    {
        return $this->createFlatXMLDataSet(__DIR__ . '/datasets/get-project.xml');
    }

    public function testNonGetRequestReturnsClientError()
    {
        $request = new Request();
        $request->setMethod(Request::METHOD_POST);
        $request->attributes->set('apiKey', 'project-api-key');
        $request->headers->set('php-auth-pw', 'valid-api-key');
        $response = $this->endpoint->respond($request);
        $json     = json_decode($response->getContent(), true);

        $this->assertTrue($response->isClientError());
        $this->assertEquals(GetProject::MUST_BE_GET, $json['code']);
        $this->assertArrayHasKey('message', $json);
    }

    public function testInvalidApiKeyReturnsClientError()
    {
        $request = $this->createRequest('Test Project');
        $request->headers->set('php-auth-pw', 'this-is-not-a-valid-api-key');
        $response = $this->endpoint->respond($request);
        $json     = json_decode($response->getContent(), true);

        $this->assertTrue($response->isClientError());
        $this->assertEquals(GetProject::AUTHENTICATION_FAILED, $json['code']);
        $this->assertArrayHasKey('message', $json);
    }

    public function testInvalidProjectKeyReturns404Response()
    {
        $request = $this->createRequest('not-a-project-key');
        $response = $this->endpoint->respond($request);
        $json     = json_decode($response->getContent(), true);

        $this->assertTrue($response->isClientError());
        $this->assertEquals(GetProject::PROJECT_NOT_FOUND, $json['code']);
        $this->assertArrayHasKey('message', $json);
    }

    private function createRequest($projectApiKey = 'project-api-key')
    {
        $request = new Request();
        $request->headers->set('php-auth-pw', 'valid-api-key');
        $request->attributes->set('apiKey', $projectApiKey);
        $request->setMethod(Request::METHOD_GET);
        return $request;
    }
}
