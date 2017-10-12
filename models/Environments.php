<?php

namespace Model;

use Dewdrop\Db\Table;

class Environments extends Table
{
    public function init()
    {
        $this
            ->setTableName('environments')
            ->setRowClass('\Model\Environments\Row');
    }

    public function getApiResponse($projectId)
    {
        $response = [];

        $environmentIds = $this->getAdapter()->fetchCol(
            'SELECT environment_id FROM environments WHERE project_id = ? ORDER BY name',
            [$projectId]
        );

        foreach ($environmentIds as $id) {
            /* @var $environment \Model\Environments\Row */
            $environment = $this->find($id);

            $response[] = $environment->getApiResponse();
        }

        return $response;
    }
}
