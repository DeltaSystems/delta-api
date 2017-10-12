<?php

/* @var $project \DeltaCli\Project */

$project
    ->setName('Delta API')
    ->addSlackHandle('@bgriffith')
    ->addSlackHandle('@darby');

$project->createEnvironment('production')
    ->setUsername('deploy')
    ->addHost('zeta.tripointhosting.com');

$project->getScript('deploy')
    ->addStep($project->rsync('.', 'zend/')->delete()->exclude('www')->exclude('logs'))
    ->addStep($project->allowWritesToRemoteFolder('zend/logs'))
    ->addStep($project->rsync('./www/', 'httpdocs/')->delete())
    ->addStep($project->ssh('cd zend && ./vendor/bin/dewdrop dbdeploy'))
    ->addStep($project->ssh('cd zend && ./vendor/bin/dewdrop db-meta'));

$project->createScript('install-vagrant', 'Create virtual host and Postgres database in Vagrant.')
    ->addStep(
        $project->getScript('vagrant:create-postgres')
            ->setDatabaseName('delta_cli')
    )
    ->addStep(
        $project->getScript('vagrant:create-postgres')
            ->setDatabaseName('delta_cli_test')
    )
    ->addStep(
        $project->getScript('vagrant:create-vhost')
            ->setHostname('delta-deploy.local')
            ->setDocumentRoot(__DIR__ . '/www')
    );

$project->createScript('run-tests', 'Run our unit tests.')
    ->addStep('run-phpunit', 'phpunit -c tests/phpunit.xml tests');

$project->createScript('run-tests-with-coverage', 'Run our unit tests and generate coverage report.')
    ->addStep('run-phpunit', 'phpunit -c tests/phpunit.xml --coverage-html=test-coverage tests');

$project->createScript('watch-tests', 'Run tests whenever a file changes.')
    ->addStep(
        $project->watch($project->getScript('run-tests'))
            ->addPath('tests')
            ->addPath('models')
            ->addPath('library')
    );
