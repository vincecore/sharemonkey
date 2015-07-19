<?php

namespace ShareMonkey\Service\Slack;

use ShareMonkey\Model\Slack\Message;

interface MessageProcessor
{
    /**
     * @param Message $message
     */
    public function process(Message $message);
}
