<?php

namespace MailerBundle\Entity\EmailHandler;

use PhpAmqpLib\Connection\AMQPConnection;

class AMQPHandler
{
    protected function getConnection()
    {
        $connection = new AMQPConnection('localhost', 5672, 'guest', 'guest');
        $channel = $connection->channel();

        $channel->queue_declare('email_queue', false, false, false, false);

        return $channel;
    }
}