<?php

namespace MailerBundle\Entity;

class Notification
{
    /**
     * @var string notification subject
     */
    private $subject;

    /**
     * @var string notification body
     */
    private $body;

    /**
     * @var string notification receiver
     */
    private $to;

    /**
     * @return string
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * @param $to
     */
    public function setTo($to)
    {
        $this->to = $to;
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param $body
     * @param $from
     */
    public function setBody($body, $from)
    {
        $this->body = $body . "\n" . "Best regards, $from";
    }

    /**
     * @param $subject
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    /**
     * @param $from
     * @return string
     */
    public function toJson($from)
    {
        $result = json_encode([
            'body' => $this->body,
            'subject' => $this->subject,
            'to' => $this->to,
            'from' => $from
        ]);
        if (json_last_error()) {
            trigger_error("Cannot encode json");
        }

        return $result;
    }
}