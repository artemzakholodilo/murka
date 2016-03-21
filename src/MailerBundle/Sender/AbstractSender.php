<?php

namespace MailerBundle\Sender;

use MailerBundle\Entity\Notification;

abstract class AbstractSender
{
    protected $transport;

    /**
     * @param $sender
     * @return mixed
     */
    public static function initial($sender)
    {
        return new $sender;
    }

    /**
     * @param Notification $notification
     * @return mixed
     */
    abstract public function send($notification);
}