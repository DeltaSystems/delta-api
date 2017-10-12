<?php

namespace Model;

use Dewdrop\Db\Table;

class EnvironmentResourceType extends Table
{
    const HOST = 1;

    const DATABASE = 2;

    const BROWSER_URL = 3;

    const SFTP_USERNAME = 4;

    const SFTP_PASSWORD = 5;

    const HTTP_AUTH_USERNAME = 6;

    const HTTP_AUTH_PASSWORD = 7;

    const DEV_ENVIRONMENT_FLAG = 8;

    public function init()
    {
        $this->setTableName('environment_resource_types');
    }
}