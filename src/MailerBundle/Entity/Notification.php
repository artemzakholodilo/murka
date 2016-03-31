<?php

namespace MailerBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class Notification
{
    /**
     * @var string notification subject
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 2,
     *      max = 50,
     *      minMessage = "Your subject must be at least {{ limit }} characters long",
     *      maxMessage = "Your subject cannot be longer than {{ limit }} characters"
     * )
     */
    private $subject;

    /**
     * @var string notification body
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 2,
     *      max = 250,
     *      minMessage = "Your message must be at least {{ limit }} characters long",
     *      maxMessage = "Your message cannot be longer than {{ limit }} characters"
     * )
     */
    private $body;

    /**
     * @var string notification body with sender name
     */
    private $bodyData;

    /**
     * @var string notification receiver
     * @Assert\NotBlank()
     * @Assert\Email(
     *     message = "The email '{{ value }}' is not a valid email."
     * )
     */
    private $to;

    /**
     * @var sender data
     * @Assert\NotBlank()
     * @Assert\Email(
     *     message = "The email '{{ value }}' is not a valid email."
     * )
     */
    private $from;

    /**
     * @return sender
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @param sender $from
     */
    public function setFrom($from)
    {
        $this->from = $from;
    }

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
     */
    public function setBody($body)
    {
        $this->body = $body;
    }

    /**
     * @param $subject
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    public function setNotificationBodyData()
    {
        $this->bodyData = $this->getBody() . "\n" .
            "Best regards, {{$this->getFrom()}}";
    }

    /**
     * @param $from
     * @return string
     */
    public function toJson($from)
    {
        $result = json_encode([
            'body' => $this->bodyData,
            'subject' => $this->subject,
            'to' => $this->to,
            'from' => $from
        ]);
        if (json_last_error()) {
            trigger_error("Cannot encode json");
        }

        return $result;
    }

    /**
     * @return string
     */
    public function getNotificationBodyData()
    {
        return $this->getBody() . "\n" . "Best regards, {{$this->getFrom()}}";
    }
}