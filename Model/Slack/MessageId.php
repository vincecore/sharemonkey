<?php

namespace ShareMonkey\Model\Slack;

use InvalidArgumentException;

final class MessageId
{
    /**
     * @var string
     */
    private $messageId;

    private function __construct($messageId)
    {
        if (empty($messageId)) {
            throw new InvalidArgumentException();
        }

        $this->messageId = $messageId;
    }

    /**
     * @param $timestamp
     * @return MessageId
     */
    public static function fromTimeStamp($timestamp)
    {
        $messageId = new MessageId(md5($timestamp));

        return $messageId;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->messageId;
    }
}
