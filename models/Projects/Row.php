<?php

namespace Model\Projects;

use Dewdrop\Db\Row as DewdropRow;
use Model\Environments;

class Row extends DewdropRow
{
    /**
     * @param $name
     * @return \Model\Environments\Row
     */
    public function getEnvironment($name)
    {
        $environmentsModel = new Environments();

        /* @var $environment \Model\Environments\Row */
        $environment = $environmentsModel->fetchRow(
            'SELECT * FROM environments WHERE name = ? AND project_id = ?',
            [$name, $this->get('project_id')]
        );

        return $environment;
    }
}