<?php

namespace DeltaApi;

use Model\Results;
use Model\Results\Row as ResultsRow;
use Model\ResultSteps;
use PHPUnit_Framework_TestCase;

class SlackNotificationTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var SlackNotification
     */
    private $slackNotification;

    public function setUp()
    {
        /* @var $resultsRow \Model\Results\Row */
        $resultsModel = new Results();
        $resultsRow   = $resultsModel->createRow();

        $this->slackNotification = new SlackNotification($resultsRow);
    }

    public function testCanSetCustomSlackChannel()
    {
        $this->assertInstanceOf(
            '\DeltaApi\SlackNotification',
            $this->slackNotification->setChannel('#custom-channel')
        );
    }

    public function testCustomChannelIsIncludedInPayload()
    {
        $this->slackNotification->setChannel('#custom');
        $this->assertArrayHasKey('channel', $this->slackNotification->getSlackPayload());
    }

    public function testCustomChannelNamesAreNormalized()
    {
        $this->slackNotification->setChannel('channel-with-no-hash');

        $payload = $this->slackNotification->getSlackPayload();
        $this->assertEquals('#channel-with-no-hash', $payload['channel']);

        $this->slackNotification->setChannel('#channel-with-hash');

        $payload = $this->slackNotification->getSlackPayload();
        $this->assertEquals('#channel-with-hash', $payload['channel']);
    }

    public function testCanAddAtMessageHandles()
    {
        /* @var $resultsRow ResultsRow|\PHPUnit_Framework_MockObject_MockObject */
        $resultsRow = $this->getMock(
            '\Model\Results\Row',
            ['getSlackText'],
            [new Results()]
        );

        $resultsRow->expects($this->once())
            ->method('getSlackText')
            ->with(['@one', '@two']);

        $notification = new SlackNotification($resultsRow);
        $notification->setAtMessageHandles(['@one', '@two']);
        $notification->getSlackPayload();
    }

    public function testPayloadIncludesTextAndAttachments()
    {
        $payload = $this->slackNotification->getSlackPayload();

        $this->assertArrayHasKey('text', $payload);
        $this->assertArrayHasKey('link_names', $payload);
        $this->assertArrayHasKey('attachments', $payload);
    }

    public function testAttachmentsAreRetrievedFromSteps()
    {
        /* @var $resultsRow ResultsRow|\PHPUnit_Framework_MockObject_MockObject */
        $resultsRow = $this->getMock(
            '\Model\Results\Row',
            ['getSteps'],
            [new Results()]
        );

        $step = $this->getMock(
            '\Model\ResultSteps\Row',
            ['getSlackAttachment'],
            [new ResultSteps()]
        );

        $step->expects($this->any())
            ->method('getSlackAttachment')
            ->willReturn(['step']);

        $resultsRow->expects($this->once())
            ->method('getSteps')
            ->willReturn([$step, $step]);

        $notification = new SlackNotification($resultsRow);
        $this->assertEquals([['step'], ['step']], $notification->getSlackPayloadAttachments());
    }

    public function testSendMethodUsesGuzzleClientToPostPayloadToHookUrl()
    {
        /* @var $resultsRow \Model\Results\Row */
        $resultsModel = new Results();
        $resultsRow   = $resultsModel->createRow();

        $guzzleClient = $this->getMock(
            '\GuzzleHttp\Client',
            ['request']
        );

        $notification = new SlackNotification($resultsRow, $guzzleClient);

        $guzzleClient->expects($this->once())
            ->method('request')
            ->with(
                'POST',
                'http://example.org',
                ['form_params' => ['payload' => json_encode($notification->getSlackPayload())]]
            );

        $notification->send();
    }
}
