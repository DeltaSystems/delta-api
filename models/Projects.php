<?php

namespace Model;

use Dewdrop\Db\Row;
use Dewdrop\Db\Table;

class Projects extends Table
{
    public function init()
    {
        $this
            ->setTableName('projects')
            ->setRowClass('\Model\Projects\Row');
    }

    public function createProject($name, Row $user)
    {
        $project = $this->createRow();
        $project
            ->set('name', $name)
            ->set('created_by_user_id', $user->get('user_id'))
            ->set('api_key', $this->generateApiKey())
            ->save();
        return $project;
    }

    public function generateApiKey()
    {
        return substr(bin2hex(random_bytes(1024)), 0, 64);
    }

    /**
     * @param $apiKey
     * @return \Model\Projects\Row
     */
    public function findByApiKey($apiKey)
    {
        return $this->fetchRow(
            'SELECT * FROM projects WHERE api_key = ?',
            [$apiKey]
        );
    }

    public function fetchListing()
    {
        return $this->getAdapter()->fetchAll(
            'SELECT project_id, name FROM projects ORDER BY project_id;'
        );
    }
}
