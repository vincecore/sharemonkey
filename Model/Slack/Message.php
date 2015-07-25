<?php

namespace ShareMonkey\Model\Slack;

use ShareMonkey\Model\Text;

/**
 * Represent a message coming from Slack
 */
final class Message
{
    /**
     * @var Text
     */
    private $text;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var UserId
     */
    private $userId;

    /**
     * @var MessageId
     */
    private $id;

    private function __construct()
    {
    }

    /**
     * @param $timestamp
     * @return Message
     */
    public static function fromSlack(
        $text,
        $timestamp,
        $user
    ) {
        $message = new Message();

        $message->text = new Text($text);
        $message->createdAt = \DateTime::createFromFormat('U.u', $timestamp);
        $message->createdAt->setTimezone(new \DateTimeZone(date_default_timezone_get()));
        $message->userId = new UserId($user);
        $message->id = MessageId::fromTimeStamp($timestamp);

        return $message;
    }

    /**
     * @return Text
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @return UserId
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @return MessageId
     */
    public function getId()
    {
        return $this->id;
    }
}
