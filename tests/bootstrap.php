<?php

if ('test' !== getenv('APPLICATION_ENV')) {
    throw new Exception(
        'Tests must be run with APPLICATION_ENV=test to allow data to be wiped and replaced with known datasets.'
    );
}

define('VENDOR_PATH', realpath(__DIR__ . '/../vendor'));

require_once VENDOR_PATH . '/autoload.php';

use Dewdrop\Pimple;

Pimple::getInstance();
