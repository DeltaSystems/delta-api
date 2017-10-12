<?php

namespace DeltaApi;

use PHPUnit_Framework_TestCase;

class EmailAddressTest extends PHPUnit_Framework_TestCase
{
    public function testAddressIsAutomaticallyTrimmedAndLowerCased()
    {
        $emailAddress = new EmailAddress('      EMAIL@EXAMPLE.org ');
        $this->assertEquals('email@example.org', $emailAddress->getAddress());
    }

    public function testCanGetDomainFromAddress()
    {
        $emailAddress = new EmailAddress('email@example.org');
        $this->assertEquals('example.org', $emailAddress->getDomain());
    }

    public function testCanCheckValidityOfAddress()
    {
        $validAddress = new EmailAddress('email@example.org');
        $this->assertTrue($validAddress->isValid());

        $invalidAddress = new EmailAddress('not-valid');
        $this->assertFalse($invalidAddress->isValid());
    }
}
