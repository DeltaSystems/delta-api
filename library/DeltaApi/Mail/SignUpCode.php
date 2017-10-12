<?php

namespace DeltaApi\Mail;

use DeltaApi\EmailAddress;
use Dewdrop\View\View;
use Zend\Mail\Message;
use Zend\Mail\Transport\Sendmail;
use Zend\Mail\Transport\TransportInterface;

class SignUpCode
{
    /**
     * @var TransportInterface
     */
    private $transport;

    /**
     * @var string
     */
    private $authorizationCode;

    /**
     * @var EmailAddress
     */
    private $toAddress;

    public function __construct(EmailAddress $toAddress, $authorizationCode, TransportInterface $transport = null)
    {
        $this->transport         = ($transport ?: new Sendmail());
        $this->authorizationCode = $authorizationCode;
        $this->toAddress         = $toAddress;
    }

    public function renderBody()
    {
        $view = new View();
        $view
            ->setScriptPath(__DIR__ . '/view-scripts')
            ->assign('authorizationCode', $this->authorizationCode);
        return $view->render('sign-up-code.phtml');
    }

    public function send(Message $message = null)
    {
        if (null === $message) {
            $message = new Message();
        }

        $message
            ->setSubject('Your Delta API sign-up verification code')
            ->addTo($this->toAddress->getAddress())
            ->setBody($this->renderBody());

        $this->transport->send($message);
    }
}
