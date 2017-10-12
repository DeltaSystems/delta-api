<?php

namespace Model;

use DeltaApi\EmailAddress;
use Dewdrop\Db\Table;

class AuthorizedEmailDomains extends Table
{
    public function init()
    {
        $this->setTableName('authorized_email_domains');
    }

    public function isAuthorizedEmailAddress(EmailAddress $emailAddress)
    {
        return in_array(
            $emailAddress->getDomain(),
            $this->getAdapter()->fetchCol(
                'SELECT LOWER(domain_name) FROM authorized_email_domains'
            )
        );
    }

    public function getInvalidAddressErrorMessage()
    {
        return sprintf(
            'New sign-ups must come from one of these authorized domains: %s.',
            implode(
                ', ',
                $this->getAdapter()->fetchCol(
                    'SELECT domain_name FROM authorized_email_domains ORDER BY domain_name'
                )
            )
        );
    }

    public function fetchIdForEmailAddress(EmailAddress $emailAddress)
    {
        return $this->getAdapter()->fetchOne(
            'SELECT authorized_email_domain_id FROM authorized_email_domains WHERE domain_name = ?',
            [$emailAddress->getDomain()]
        );
    }
}
