<?php

namespace MailerBundle\Entity\EmailHandler;

use MailerBundle\Sender\MailSender;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class EmailReceiver extends AMQPHandler
{
    /**
     * @var AMQPStreamConnection $connection
     */
    private $connection;

    /**
     * @var MailSender $sender
     */
    private $sender;

    /**
     * EmailReceiver constructor.
     * @param MailSender $sender
     */
    public function __construct(MailSender $sender)
    {
        $this->connection = $this->getConnection();
        $this->sender = $sender;
    }

    public function listen()
    {
        $callback = function($message)
        {
            $data = json_decode($message->body, true);
            $this->sender->send($data);
        };

        $this->connection->basic_qos(null, 1, null);
        $this->connection->basic_consume('email_queue', '', false, false, false, false, $callback);

        while(count($this->connection->callbacks)) {
            $this->connection->wait();
        }
    }
}