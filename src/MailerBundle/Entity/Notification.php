<?php

namespace MailerBundle\Entity;

class Notification
{
    private $subject;

    private $body;

    public function getBody()
    {
        return $this->body;
    }

    public function getSubject()
    {
        return $this->subject;
    }

    public function setBody($body, $from)
    {
        $this->body = $body . "\n" . "Best regards, $from";
    }

    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    public function toJson()
    {
        $result = json_encode([
            $this->body,
            $this->subject
        ]);
        if (json_last_error()) {
            trigger_error("Cannot encode json");
        }

        return $result;
    }
}