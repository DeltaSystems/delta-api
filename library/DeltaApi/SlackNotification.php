<?php

namespace DeltaApi;

use Dewdrop\Pimple;
use GuzzleHttp\Client as GuzzleClient;
use Model\Results\Row as ResultsRow;
use Model\ResultSteps\Row as StepRow;

class SlackNotification
{
    /**
     * @var GuzzleClient
     */
    private $guzzleClient;

    /**
     * @var ResultsRow
     */
    private $results;

    /**
     * @var string
     */
    private $channel;

    /**
     * @var array
     */
    private $atMessageHandles = [];

    /**
     * SlackNotification constructor.
     * @param GuzzleClient $guzzleClient
     * @param ResultsRow $results
     */
    public function __construct(ResultsRow $results, GuzzleClient $guzzleClient = null, $slackHookUrl = null)
    {
        $this->results      = $results;
        $this->guzzleClient = ($guzzleClient ?: Pimple::getResource('guzzle-client'));
        $this->slackHookUrl = ($slackHookUrl ?: Pimple::getResource('slack-hook-url'));
    }

    public function send()
    {
        return $this->guzzleClient->request(
            'POST',
            $this->slackHookUrl,
            [
                'form_params' => [
                    'payload' => json_encode($this->getSlackPayload())
                ]
            ]
        );
    }

    public function getSlackPayload()
    {
        $payloadData = [
            'text'        => $this->results->getSlackText($this->atMessageHandles),
            'attachments' => $this->getSlackPayloadAttachments(),
            'link_names'  => 1
        ];

        if ($this->channel) {
            $payloadData['channel'] = $this->channel;
        }

        return $payloadData;
    }

    public function getSlackPayloadAttachments()
    {
        $attachments = [];

        /* @var $step StepRow */
        foreach ($this->results->getSteps() as $step) {
            $attachments[] = $step->getSlackAttachment();
        }

        return $attachments;
    }

    public function setChannel($channel)
    {
        if ($channel) {
            $this->channel = '#' . ltrim($channel, '#');
        }

        return $this;
    }

    public function setAtMessageHandles(array $atMessageHandles)
    {
        $this->atMessageHandles = array_map(
            function ($handle) {
                return '@' . ltrim($handle, '@');
            },
            $atMessageHandles
        );

        return $this;
    }
}
