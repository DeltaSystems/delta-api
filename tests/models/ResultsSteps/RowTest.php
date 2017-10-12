<?php

namespace Model\ResultSteps;

use Model\ResultSteps;
use PHPUnit_Framework_TestCase;

class RowTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Row
     */
    private $row;

    public function setUp()
    {
        $model = new ResultSteps();

        $this->row = $model->createRow(
            [
                'status_message'   => 'This is my message.',
                'output'           => 'This is my output.',
                'result_status_id' => 1
            ]
        );
    }

    public function testStatusColorsIsFetchedFromDb()
    {
        $this->assertEquals('good', $this->row->getSlackStatusColor());
    }

    public function testSlackAttachmentIncludesExpectedFields()
    {
        $attachment = $this->row->getSlackAttachment();

        $this->assertEquals($this->row->get('status_message'), $attachment['fallback']);
        $this->assertEquals($this->row->get('status_message'), $attachment['title']);
        $this->assertEquals($this->row->get('output'), $attachment['text']);
        $this->assertEquals($this->row->getSlackStatusColor(), $attachment['color']);
    }
}
