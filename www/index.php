<?php

// On prod, most code is tucked into zend outside the doc root
if (file_exists(__DIR__ . '/../zend/')) {
    define('APPLICATION_PATH', realpath(__DIR__ . '/../zend/'));
} else {
    define('APPLICATION_PATH', realpath(__DIR__ . '/../'));
}

require_once APPLICATION_PATH . '/vendor/autoload.php';

use DeltaApi\Routes\VersionOne as VersionOneRoutes;
use Dewdrop\Pimple;

/* @var $silex \Silex\Application */
$silex  = Pimple::getInstance();
$routes = new VersionOneRoutes($silex);

if ('delta-deploy.local:8080' === $_SERVER['HTTP_HOST']) {
    $silex['debug'] = true;
}

$silex->get(
    '/',
    function () {
        return file_get_contents(__DIR__ . '/home.html');
    }
);

$routes
    ->createRoutes()
    ->addRoutesToSilex();

$silex->run();
