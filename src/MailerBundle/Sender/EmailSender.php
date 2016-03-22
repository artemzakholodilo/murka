<?php

namespace MailerBundle\Sender;

use MailerBundle\Entity\Notification;

class EmailSender extends AbstractSender
{
    protected $transport;

    /**
     * EmailSender constructor.
     * @param \Swift_Mailer $mailer
     * @param $name
     * @param $password
     */
    public function __construct(
        \Swift_Mailer $mailer,
        $name,
        $password
    )
    {
        $transport = \Swift_SmtpTransport::newInstance('gmail')
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