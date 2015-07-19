<?php

namespace ShareMonkey\Model\Slack;

/**
 * Represent a reaction on a message from slack
 */
final class Reaction
{
    const REACTION_LIKE = '+1';
    const REACTION_DISLIKE = '-1';

    /**
     * @var MessageId
     */
    private $messageId;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var UserId
     */
    private $userId;

    /**
     * @var string
     */
    private $reaction;

    private function __construct()
    {
    }

    /**
     * @param $reaction
     * @param $timestamp
     * @param $messageTimestamp
     * @param $user
     * @return Reaction
     */
    public static function fromSlack(
        $reaction,
        $timestamp,
        $messageTimestamp,
        $user
    ) {

        $message = new Reaction();

        $message->messageId = MessageId::fromTimeStamp($messageTimestamp);
        $message->userId = new UserId($user);
        $message->reaction = $reaction;
        $message->createdAt = \DateTime::createFromFormat('U.u', $timestamp);
        $message->createdAt->setTimezone(new \DateTimeZone(date_default_timezone_get()));

        return $message;
    }

    /**
     * @return UserId
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @return MessageId
     */
    public function getMessageId()
    {
        return $this->messageId;
    }

    /**
     * @return bool
     */
    public function isLike()
    {
        return $this->reaction === self::REACTION_LIKE;
    }

    /**
     * @return bool
     */
    public function isDislike()
    {
        return $this->reaction === self::REACTION_DISLIKE;
    }
}
