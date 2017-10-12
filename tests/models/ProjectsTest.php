<?php

namespace Model;

use Dewdrop\Test\DbTestCase;

class ProjectsTest extends DbTestCase
{
    /**
     * @var Projects
     */
    private $model;

    protected function getDataSet()
    {
        return $this->createFlatXMLDataSet(__DIR__ . '/datasets/projects.xml');
    }

    public function setUp()
    {
        parent::setUp();

        $this->model = new Projects();
    }

    public function testGenerateApiKeyReturnsA64CharacterRandomString()
    {
        $firstKey  = $this->model->generateApiKey();
        $secondKey = $this->model->generateApiKey();
        $this->assertEquals(64, strlen($firstKey));
        $this->assertNotEquals($firstKey, $secondKey);
    }

    public function testCanCreateProject()
    {
        $usersModel = new Users();

        $project = $this->model->createProject('Test Project', $usersModel->find(20));

        $this->assertNotNull($project->get('api_key'));
        $this->assertEquals(20, $project->get('created_by_user_id'));
        $this->assertEquals('Test Project', $project->get('name'));
    }
}
