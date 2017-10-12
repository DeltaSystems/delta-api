<?php

namespace DeltaApi\Mail;

use DeltaApi\EmailAddress;
use PHPUnit_Framework_TestCase;

class SignUpCodeTest extends PHPUnit_Framework_TestCase
{
    public function testBodyContainsAuthorizationCode()
    {
        $message = new SignUpCode(new EmailAddress('bgriffith@deltasys.com'), 'authorization-code-example');
        $this->assertContains('authorization-code-example', $message->renderBody());
    }

    public function testMessageIsSentToPassedInEmailAddress()
    {
        /* @var $message \Zend\Mail\Message|\PHPUnit_Framework_MockObject_MockObject */
        $message = $this->getMock(
            '\Zend\Mail\Message',
            ['addTo']
        );

        $message->expects($this->once())
            ->method('addTo')
            ->with('bgriffith@deltasys.com')
            ->willReturn($message);

        /* @var $mockTransport \Zend\Mail\Transport\TransportInterface */
        $mockTransport = $this->getMock('\Zend\Mail\Transport\TransportInterface');

        $signUpCode = new SignUpCode(new EmailAddress('bgriffith@deltasys.com'), '123456', $mockTransport);
        $signUpCode->send($message);
    }
}
