<?php

namespace Model\Results;

use Dewdrop\Db\Row as DewdropRow;
use Model\ResultSteps;

class Row extends DewdropRow
{
    public function getSlackText(array $atMessageHandles)
    {
        return sprintf(
            '%s*%s* ran the *%s* %s script%s.',
            (count($atMessageHandles) ? implode(' ', $atMessageHandles) . ' ': ''),
            $this->getUserEmailAddress(),
            $this->getProjectName(),
            $this->get('script'),
            $this->getSlackEnvironmentClause()
        );
    }

    public function getSlackEnvironmentClause()
    {
        if (!$this->get('environment')) {
            return '';
        }

        return sprintf(' on the *%s* environment', $this->get('environment'));
    }

    public function getUserEmailAddress()
    {
        return $this->getTable()->getAdapter()->fetchOne(
            'SELECT email_address FROM users WHERE user_id = ?',
            [$this->get('run_by_user_id')]
        );
    }

    public function getProjectName()
    {
        return $this->getTable()->getAdapter()->fetchOne(
            'SELECT name FROM projects WHERE project_id = ?',
            [$this->get('project_id')]
        );
    }

    public function getSteps()
    {
        $stepModel = new ResultSteps();
        $stepRows  = [];

        foreach ($this->fetchStepData() as $stepData) {
            $stepRows[] = $stepModel->createRow($stepData);
        }

        return $stepRows;
    }

    private function fetchStepData()
    {
        return $this->getTable()->getAdapter()->fetchAll(
            'SELECT * FROM result_steps WHERE result_id = ?',
            [$this->get('result_id')]
        );
    }
}
