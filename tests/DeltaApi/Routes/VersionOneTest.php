<?php

namespace DeltaApi\Routes;

use PHPUnit_Framework_TestCase;
use Silex\Application as Silex;

class VersionOneTest extends PHPUnit_Framework_TestCase
{
    /**
     * Really basic tests in this case because this exact code is run on every single request.
     * Just need to make sure no obvious breakages occur.
     */
    public function testCanSuccessfullyAddAllRoutesToSilex()
    {
        $silex = new Silex();

        $versionOne = new VersionOne($silex);

        $versionOne
            ->createRoutes()
            ->addRoutesToSilex();
    }
}
