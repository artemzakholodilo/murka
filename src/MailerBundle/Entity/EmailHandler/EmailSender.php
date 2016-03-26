<?php

namespace MailerBundle\Entity\EmailHandler;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class EmailSender extends AMQPHandler
{
    /**
     * @var AMQPStreamConnection
     */
    private $connection;

    /**
     * EmailSender constructor.
     */
    public function __construct()
    {
        $this->connection = $this->getConnection();
    }

    /**
     * @param $message
     * @return AMQPMessage
     */
    public function send($message)
    {
        $message = new AMQPMessage($message, ['delivery_mode' => 2]);
        $this->connection->basic_publish($message, '', 'email_queue');

        return $message;
    }
}