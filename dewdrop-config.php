<?php

return [
    'bootstrap' => '\DeltaApi\Bootstrap',
    'development' => [
        'db' => [
            'username' => 'delta_cli',
            'password' => 'delta_cli',
            'host'     => 'localhost',
            'name'     => 'delta_cli',
            'type'     => 'pgsql'
        ],
        'slack-hook-url' => 'http://example.org'
    ],
    'test' => [
        'db' => [
            'username' => 'delta_cli_test',
            'password' => 'delta_cli_test',
            'host'     => 'localhost',
            'name'     => 'delta_cli_test',
            'type'     => 'pgsql'
        ],
        'slack-hook-url' => 'http://example.org'
    ],
    'production' => [
        'db' => [
            'username' => 'deltasys_deploy',
            'password' => 'yWwlh6zF93yQd13C',
            'host'     => 'easytrc-postgres.cpdpt5ayyljj.us-east-2.rds.amazonaws.com',
            'name'     => 'deltasys_deploy',
            'type'     => 'pgsql'
        ],
        'slack-hook-url' => 'http://example.org'
    ]
];

