<?php

namespace DeltaApi\Routes;

use DeltaApi\Endpoint\CreateDatabase;
use DeltaApi\Endpoint\CreateEnvironment;
use DeltaApi\Endpoint\CreateProject;
use DeltaApi\Endpoint\CreateUser;
use DeltaApi\Endpoint\GetEnvironment;
use DeltaApi\Endpoint\GetProject;
use DeltaApi\Endpoint\ListEnvironments;
use DeltaApi\Endpoint\Log;
use DeltaApi\Endpoint\Login;
use DeltaApi\Endpoint\ProjectLog;
use DeltaApi\Endpoint\Projects as ProjectsEndpoint;
use DeltaApi\Endpoint\Results as ResultsEndpoint;
use DeltaApi\Endpoint\SignUpWithEmail;
use DeltaApi\Endpoint\Users as UsersEndpoint;
use Model\AuthorizedEmailDomains;
use Model\Environments;
use Model\Projects;
use Model\Results;
use Model\SignUpRequests;
use Model\Users;
use Silex\Application as Silex;

class VersionOne
{
    /**
     * @var Silex
     */
    private $silex;

    /**
     * @var array
     */
    private $routes = [];

    public function __construct(Silex $silex)
    {
        $this->silex = $silex;
    }

    public function getUrlPrefix()
    {
        return 'v1';
    }

    public function addRoute($url, $method, callable $factory)
    {
        $this->routes[] = new Route($url, $method, $factory);

        return $this;
    }

    public function addRoutesToSilex()
    {
        /* @var $route Route */
        foreach ($this->routes as $route) {
            $route->addToSilex($this->silex, $this->getUrlPrefix());
        }

        return $this;
    }

    public function createRoutes()
    {
        $this->addRoute(
            '/sign-up-with-email',
            'post',
            function () {
                return new SignUpWithEmail(
                    $this->silex,
                    new Users(),
                    new SignUpRequests(),
                    new AuthorizedEmailDomains()
                );
            }
        );

        $this->addRoute(
            '/account',
            'post',
            function () {
                return new CreateUser($this->silex, new Users(), new SignUpRequests());
            }
        );

        $this->addRoute(
            '/users',
            'get',
            function () {
                return new UsersEndpoint($this->silex, new Users());
            }
        );

        $this->addRoute(
            '/login',
            'post',
            function () {
                return new Login($this->silex, new Users());
            }
        );

        $this->addRoute(
            '/log',
            'get',
            function () {
                return new Log($this->silex, new Results(), new Users());
            }
        );

        $this->addRoute(
            '/projects',
            'get',
            function () {
                return new ProjectsEndpoint($this->silex, new Projects(), new Users());
            }
        );

        $this->addRoute(
            '/project',
            'post',
            function () {
                return new CreateProject($this->silex, new Projects(), new Users());
            }
        );

        $this->addRoute(
            '/project/{apiKey}',
            'get',
            function () {
                return new GetProject($this->silex, new Projects(), new Users());
            }
        );

        $this->addRoute(
            '/project/{apiKey}/log',
            'get',
            function () {
                return new ProjectLog($this->silex, new Results(), new Projects(), new Users());
            }
        );

        $this->addRoute(
            '/project/{apiKey}/environments',
            'get',
            function () {
                return new ListEnvironments($this->silex, new Projects(), new Environments(), new Users());
            }
        );

        $this->addRoute(
            '/project/{apiKey}/environment',
            'post',
            function () {
                return new CreateEnvironment($this->silex, new Projects(), new Environments(), new Users());
            }
        );

        $this->addRoute(
            '/project/{apiKey}/environment/{environmentName}',
            'get',
            function () {
                return new GetEnvironment($this->silex, new Projects(), new Users());
            }
        );

        $this->addRoute(
            '/project/{apiKey}/environment/{environmentName}/database',
            'post',
            function () {
                return new CreateDatabase($this->silex, new Projects(), new Environments(), new Users());
            }
        );

        $this->addRoute(
            '/results',
            'post',
            function () {
                return new ResultsEndpoint($this->silex, new Results(), new Projects(), new Users());
            }
        );

        return $this;
    }
}
