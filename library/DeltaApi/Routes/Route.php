<?php

namespace DeltaApi\Routes;

use DeltaApi\Endpoint\EndpointInterface;
use Silex\Application as Silex;
use Symfony\Component\HttpFoundation\Request;

class Route
{
    /**
     * @var string
     */
    private $url;

    /**
     * @var string
     */
    private $method;

    /**
     * @var callable
     */
    private $factory;

    public function __construct($url, $method, callable $factory)
    {
        $this->url     = '/' . ltrim($url, '/');
        $this->method  = $method;
        $this->factory = $factory;
    }

    public function addToSilex(Silex $silex, $urlPrefix)
    {
        $method   = $this->method;
        $factory  = $this->factory;
        $endpoint = $factory();

        if (!$endpoint instanceof EndpointInterface) {
            throw new InvalidEndpointException('Route factories must return EndpointInterface objects.');
        }

        $silex->$method(
            "/{$urlPrefix}{$this->url}",
            $this->createSilexController($endpoint)
        );
    }

    public function createSilexController(EndpointInterface $endpoint)
    {
        return function (Request $request) use ($endpoint) {
            return $endpoint->respond($request);
        };
    }
}
