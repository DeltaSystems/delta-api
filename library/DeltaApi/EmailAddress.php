<?php

namespace DeltaApi;

class EmailAddress
{
    private $emailAddress;

    public function __construct($emailAddress)
    {
        $this->emailAddress = trim(strtolower($emailAddress));
    }

    public function isValid()
    {
        return (false !== filter_var($this->emailAddress, FILTER_VALIDATE_EMAIL));
    }

    public function getAddress()
    {
        return $this->emailAddress;
    }

    public function getDomain()
    {
        return strtolower(substr($this->emailAddress, strrpos($this->emailAddress, '@') + 1));
    }
}
