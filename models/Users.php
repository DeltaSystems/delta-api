<?php

namespace Model;

use DeltaApi\EmailAddress;
use Dewdrop\Db\Table;

class Users extends Table
{
    public function init()
    {
        $this->setTableName('users');
    }

    public function accountExistsWithEmailAddress(EmailAddress $emailAddress)
    {
        return (boolean)$this->getAdapter()->fetchOne(
            'SELECT true FROM users WHERE LOWER(email_address) = ?',
            [$emailAddress->getAddress()]
        );
    }

    public function passwordsMatch($password, $confirmPassword)
    {
        return $password === $confirmPassword;
    }

    public function passwordIsSuitablyComplex($password)
    {
        return 12 <= strlen($password);
    }

    public function hashPassword($plaintextPassword)
    {
        return password_hash($plaintextPassword, PASSWORD_DEFAULT);
    }

    public function generateApiKey()
    {
        return substr(bin2hex(random_bytes(1024)), 0, 64);
    }

    public function passwordIsCorrect($userId, $plaintextPassword)
    {
        $hashedPassword = $this->getAdapter()->fetchOne(
            'SELECT password_hash FROM users WHERE user_id = ?',
            [$userId]
        );

        return password_verify($plaintextPassword, $hashedPassword);
    }

    public function createAccount(EmailAddress $emailAddress, $plaintextPassword)
    {
        $account = $this->createRow();
        $account
            ->set('email_address', $emailAddress->getAddress())
            ->set('password_hash', $this->hashPassword($plaintextPassword))
            ->set('api_key', $this->generateApiKey())
            ->save();
        return $account;
    }

    public function findByEmailAddress(EmailAddress $emailAddress)
    {
        return $this->fetchRow(
            'SELECT * FROM users WHERE email_address = ?',
            [$emailAddress->getAddress()]
        );
    }

    public function findByApiKey($apiKey)
    {
        return $this->fetchRow(
            'SELECT * FROM users WHERE api_key = ?',
            [$apiKey]
        );
    }

    public function fetchListing()
    {
        return $this->getAdapter()->fetchAll(
            'SELECT user_id, email_address FROM users ORDER BY user_id;'
        );
    }
}
