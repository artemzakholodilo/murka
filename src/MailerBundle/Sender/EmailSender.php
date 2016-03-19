<?php

namespace MailerBundle\Sender;

use MailerBundle\Entity\Notification;

class EmailSender extends AbstractSender
{
    protected $transport;

    private $userName = 'azaholodilo@gmail.com';

    /**
     * EmailSender constructor.
     * @param \Swift_Mailer $mailer
     * @param \Swift_SmtpTransport $smtpTransport
     */
    public function __construct(\Swift_Mailer $mailer, \Swift_SmtpTransport $smtpTransport)
    {
        $transport = $smtpTransport::newInstance('gmail')
            ->setUsername($this->userName)
            ->setPassword('db1708j30a2');

        $this->transport = $mailer::newInstance($transport);
    }

    /**
     * @param Notification $notification
     */
    public function send($notification)
    {

        $message = \Swift_Message::newInstance($notification['subject'])
            ->setFrom([$notification['from']])
            ->setTo([$notification['to']])
            ->setBody($notification['body']);

        $this->transport->send($message);
    }
}