<?php

namespace Model;

use DeltaApi\EmailAddress;
use Dewdrop\Test\DbTestCase;

class UsersTest extends DbTestCase
{
    /**
     * @var Users
     */
    private $model;

    protected function getDataSet()
    {
        return $this->createFlatXMLDataSet(__DIR__ . '/datasets/users.xml');
    }

    public function setUp()
    {
        parent::setUp();

        $this->model = new Users();
    }

    public function testExistingAccountIsDetectedAccurately()
    {
        $emailAddress = new EmailAddress('bgriffith@deltasys.com');
        $this->assertTrue($this->model->accountExistsWithEmailAddress($emailAddress));

        $emailAddress = new EmailAddress('new-user@example.org');
        $this->assertFalse($this->model->accountExistsWithEmailAddress($emailAddress));
    }

    public function testPasswordMustMeetComplexityRequirements()
    {
        $this->assertFalse($this->model->passwordIsSuitablyComplex('1234'));
        $this->assertTrue($this->model->passwordIsSuitablyComplex('dfafadfafdafdqeru'));
    }

    public function testPasswordsMustMatch()
    {
        $this->assertFalse($this->model->passwordsMatch('no-match', 'nope'));
        $this->assertTrue($this->model->passwordsMatch('match', 'match'));
    }

    public function testHashPasswordAtLeastDoesNotReturnThePlaintext()
    {
        $this->assertNotEquals('password', $this->model->hashPassword('password'));
    }

    public function testGenerateApiKeyReturnsA64CharacterRandomString()
    {
        $firstKey  = $this->model->generateApiKey();
        $secondKey = $this->model->generateApiKey();
        $this->assertEquals(64, strlen($firstKey));
        $this->assertNotEquals($firstKey, $secondKey);
    }

    public function testCanCreateAccount()
    {
        $user = $this->model->createAccount(new EmailAddress('newuser@deltasys.com'), 'plaintext-password');

        $this->assertEquals(1, $user->get('user_id'));
        $this->assertNotEquals('plaintext-password', $user->get('password_hash'));
        $this->assertEquals(64, strlen($user->get('api_key')));
    }

    public function testCanComparePasswords()
    {
        $user = $this->model->createAccount(new EmailAddress('newuser@deltasys.com'), 'plaintext-password');

        $this->assertTrue($this->model->passwordIsCorrect($user->get('user_id'), 'plaintext-password'));
    }

    public function testCanFindUserByEmailAddress()
    {
        $this->assertEquals(
            'bgriffith@deltasys.com',
            $this->model->findByEmailAddress(new EmailAddress('bgriffith@deltasys.com'))->get('email_address')
        );
    }

    public function testCanFindUserByApiKey()
    {
        $this->assertEquals(
            'xxx',
            $this->model->findByApiKey('xxx')->get('api_key')
        );
    }
}
