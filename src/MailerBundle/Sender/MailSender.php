<?php

namespace MailerBundle\Sender;

class MailSender extends AbstractSender
{
    /**
     * @var \Swift_Mailer
     */
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
        $transport = \Swift_SmtpTransport::newInstance("smtp.gmail.com", 465, "ssl")
            ->setUsername($name)
            ->setPassword($password);

        $this->transport = $mailer::newInstance($transport);
    }

    /**
     * @param array $notification
     * @return null
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