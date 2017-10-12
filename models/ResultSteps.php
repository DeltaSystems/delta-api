<?php

namespace Model;

use Dewdrop\Db\Table;

class ResultSteps extends Table
{
    public function init()
    {
        $this
            ->setTableName('result_steps')
            ->setRowClass('\Model\ResultSteps\Row');
    }
}
