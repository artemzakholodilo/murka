<?php

namespace MailerBundle\Sender;

abstract class AbstractSender
{
    protected $transport;

    public static function initial($sender)
    {
        return new $sender;
    }

    abstract public function send($notification);
}