<?php

namespace Model;

use DeltaApi\EmailAddress;
use DeltaApi\Mail\SignUpCode;
use Dewdrop\Db\Table;
use Zend\Mail\Transport\TransportInterface;

class SignUpRequests extends Table
{
    public function init()
    {
        $this->setTableName('sign_up_requests');
    }

    public function createForAuthorizedDomain(
        EmailAddress $emailAddress,
        AuthorizedEmailDomains $authorizedEmailDomainsModel,
        TransportInterface $mailTransport = null
    ) {
        $authorizationCode = $this->generateAuthorizationCode();

        $this->insert(
            [
                'email_address'              => $emailAddress->getAddress(),
                'authorized_email_domain_id' => $authorizedEmailDomainsModel->fetchIdForEmailAddress($emailAddress),
                'authorization_code'         => $authorizationCode
            ]
        );

        $message = new SignUpCode($emailAddress, $authorizationCode, $mailTransport);
        $message->send();
    }

    public function generateAuthorizationCode()
    {
        return substr(bin2hex(random_bytes(128)), 0, 32);
    }

    public function findByAuthorizationCode($authorizationCode)
    {
        return $this->fetchRow(
            'SELECT * FROM sign_up_requests WHERE authorization_code = ?',
            [$authorizationCode]
        );
    }
}
