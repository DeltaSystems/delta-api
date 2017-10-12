<?php

namespace Model;

use DateTimeImmutable;
use Dewdrop\Db\Row;
use Dewdrop\Db\Table;

class Results extends Table
{
    public function init()
    {
        $this
            ->setTableName('results')
            ->setRowClass('\Model\Results\Row');
    }

    public function validateResults($results)
    {
        if (!is_array($results)) {
            return false;
        }

        $jsonKeys     = array_keys($results);
        $requiredKeys = ['script', 'environment', 'steps', 'attributes'];

        if (count($requiredKeys) !== count(array_intersect($jsonKeys, $requiredKeys))) {
            return false;
        }

        if (!is_array($results['steps']) || !is_array($results['attributes'])) {
            return false;
        }

        if (!is_string($results['script']) || !$results['script']) {
            return false;
        }

        if ($results['environment'] && !is_string($results['environment'])) {
            return false;
        }

        $validStatuses    = $this->getAdapter()->fetchCol('SELECT code FROM result_statuses');
        $validOutputTypes = $this->getAdapter()->fetchCol('SELECT name FROM result_output_types');

        foreach ($results['steps'] as $step) {
            $jsonKeys     = array_keys($step);
            $requiredKeys = ['name', 'status', 'status_message', 'output_type', 'output'];

            if (count($requiredKeys) !== count(array_intersect($jsonKeys, $requiredKeys))) {
                return false;
            }

            if (!is_string($step['name']) || !$step['name']) {
                return false;
            }

            if (!in_array($step['status'], $validStatuses)) {
                return false;
            }

            if (!in_array($step['output_type'], $validOutputTypes)) {
                return false;
            }
        }

        return true;
    }

    public function saveResults(array $results, Row $project, Row $user)
    {
        $row = $this->createRow();

        if (!isset($results['status']) || !$results['status']) {
            $results['status'] = 'success';

            foreach ($results['steps'] as $stepResult) {
                if ('failure' === $stepResult['status']) {
                    $results['status'] = 'failure';
                    break;
                }
            }
        }

        $row
            ->set('project_id', $project->get('project_id'))
            ->set('script', $results['script'])
            ->set('environment', $results['environment'])
            ->set('result_status_id', $this->fetchStatusIdByName($results['status']))
            ->set('run_by_user_id', $user->get('user_id'))
            ->save();

        $db = $this->getAdapter();

        foreach ($results['steps'] as $stepIndex => $stepResult) {
            $db->insert(
                'result_steps',
                [
                    'result_id'             => $row->get('result_id'),
                    'step_number'           => $stepIndex,
                    'name'                  => $stepResult['name'],
                    'result_status_id'      => $this->fetchStatusIdByName($stepResult['status']),
                    'status_message'        => $stepResult['status_message'],
                    'result_output_type_id' => $this->fetchOutputTypeIdByName($stepResult['output_type']),
                    'output'                => $stepResult['output']
                ]
            );
        }

        // @todo Save attributes

        return $row;
    }

    public function fetchStatusIdByName($name)
    {
        return $this->getAdapter()->fetchOne(
            'SELECT result_status_id FROM result_statuses WHERE code = ?',
            [$name]
        );
    }

    public function fetchOutputTypeIdByName($name)
    {
        return $this->getAdapter()->fetchOne(
            'SELECT result_output_type_id FROM result_output_types WHERE name = ?',
            [$name]
        );
    }

    public function fetchLog($script, $environment, DateTimeImmutable $after)
    {
        $entries = $this->getAdapter()->fetchAll(
            $this->selectLog($script, $environment)
                ->where('date_run > ?', $after->format('Y-m-d H:i:s'))
        );

        return $this->augmentLogEntriesWithSteps($entries);
    }

    public function fetchProjectLog(Row $project, $script, $environment)
    {
        $select = $this->selectLog($script, $environment)
            ->where('project_id = ?', $project->get('project_id'));

        $entries = $this->getAdapter()->fetchAll($select);

        return $this->augmentLogEntriesWithSteps($entries);
    }

    private function augmentLogEntriesWithSteps(array $entries)
    {
        foreach ($entries as $index => $entry) {
            $entry['steps'] = $this->getAdapter()->fetchAll(
                'SELECT s.*, u.code AS status 
                FROM result_steps s
                JOIN result_statuses u ON u.result_status_id = s.result_status_id
                WHERE result_id = ? 
                ORDER BY step_number',
                [$entry['result_id']]
            );

            $entries[$index] = $entry;
        }

        return $entries;
    }

    private function selectLog($script, $environment)
    {
        $select = $this->select()
            ->from(['r' => 'results'])
            ->join(['u' => 'users'], 'u.user_id = r.run_by_user_id', ['run_by_user' => 'email_address'])
            ->join(
                ['s' => 'result_statuses'],
                's.result_status_id = r.result_status_id',
                ['status' => 's.code']
            )
            ->order('date_run DESC')
            ->limit(500);

        if ($script) {
            $select->where('script = ?', $script);
        }

        if ($environment) {
            $select->where('environment = ?', $environment);
        }

        return $select;
    }
}
