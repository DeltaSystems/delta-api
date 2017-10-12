<?php

namespace DeltaApi;

use PHPUnit_Framework_TestCase;
use Silex\Application;

class BootstrapTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Bootstrap
     */
    private $bootstrap;

    public function setUp()
    {
        $this->bootstrap = new Bootstrap();
    }

    public function testBootstrapResourcesAreAccessibleOnSilexObject()
    {
        $silex = $this->bootstrap->getPimple();

        $this->assertInstanceOf('\Silex\Application', $silex);

        $this->assertTrue(is_array($silex['config']));
        $this->assertInstanceOf('\Dewdrop\Db\Adapter', $silex['db']);
        $this->assertInstanceOf('\Dewdrop\Paths', $silex['paths']);
    }
}
