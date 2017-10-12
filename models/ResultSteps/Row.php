<?php

namespace Model\ResultSteps;

use Dewdrop\Db\Row as DewdropRow;

class Row extends DewdropRow
{
    public function getSlackAttachment()
    {
        return [
            'fallback' => $this->get('status_message'),
            'title'    => $this->get('status_message'),
            'text'     => $this->get('output'),
            'color'    => $this->getSlackStatusColor()
        ];
    }

    public function getSlackStatusColor()
    {
        return $this->getTable()->getAdapter()->fetchOne(
            'SELECT slack_color FROM result_statuses WHERE result_status_id = ?',
            [$this->get('result_status_id')]
        );
    }
}
