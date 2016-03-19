<?php

namespace MailerBundle\Entity\EmailHandler;

use MailerBundle\Sender\EmailSender;
use PhpAmqpLib\Connection\AMQPConnection;

class EmailReceiver extends AMQPHandler
{
    /**
     * @var AMQPConnection
     */
    private $connection;

    /**
     * @var EmailSender
     */
    private $sender;

    /**
     * EmailReceiver constructor.
     * @param EmailSender $sender
     */
    public function __construct(EmailSender $sender)
    {
        $this->connection = $this->getConnection();
        $this->sender = $sender;
    }

    public function receive($message)
    {
        $callback = function() use ($message)
        {
            $data = json_decode($message->body, true);
            $this->sender->send($data);

            $message->delivery_info('channel')
                ->basic_ack($message->delivery_info('delivery_tag'));

        };

        $this->connection->basic_qos(null, 1, null);
        $this->connection->basic_consume('email_queue', '', false, false, false, false, $callback);

        while(count($this->connection->callbacks)) {
            $this->connection->wait();
        }

        return $message;
    }
}