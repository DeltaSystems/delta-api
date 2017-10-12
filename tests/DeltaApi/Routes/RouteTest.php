<?php

namespace DeltaApi\Routes;

use PHPUnit_Framework_TestCase;

class RouteTest extends PHPUnit_Framework_TestCase
{
    public function testMethodIsUsedWhenAddingToSilex()
    {
        $factory = function () {
            return $this->getMock('\DeltaApi\Endpoint\EndpointInterface');
        };

        $route = new Route('/test', 'post', $factory);

        /* @var $silex \Silex\Application|\PHPUnit_Framework_MockObject_MockObject */
        $silex = $this->getMock(
            '\Silex\Application',
            ['post'],
            []
        );

        $silex->expects($this->once())
            ->method('post')
            ->with('/v1/test', $factory);

        $route->addToSilex($silex, 'v1');
    }

    /**
     * @expectedException \DeltaApi\Routes\InvalidEndpointException
     */
    public function testFactoryThatFailsToProduceEndpointThrowsException()
    {
        $factory = function () {
            return false;
        };

        $route = new Route('/test', 'post', $factory);

        /* @var $silex \Silex\Application|\PHPUnit_Framework_MockObject_MockObject */
        $silex = $this->getMock(
            '\Silex\Application',
            ['post'],
            []
        );

        $route->addToSilex($silex, 'v1');
    }
}
