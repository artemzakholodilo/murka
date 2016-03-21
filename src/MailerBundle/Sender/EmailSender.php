<?php

namespace MailerBundle\Sender;

use MailerBundle\Entity\Notification;

class EmailSender extends AbstractSender
{
    protected $transport;

    /**
     * EmailSender constructor.
     * @param \Swift_Mailer $mailer
     * @param \Swift_SmtpTransport $smtpTransport
     * @param $name
     * @param $password
     */
    public function __construct(
        \Swift_Mailer $mailer,
        \Swift_SmtpTransport $smtpTransport,
        $name,
        $password
    )
    {
        $transport = $smtpTransport::newInstance('gmail')
            ->setUsername($name)
            ->setPassword($password);

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