<?php

namespace ShareMonkey\Model\Slack;

use InvalidArgumentException;

final class UserId
{
    /**
     * @var string
     */
    private $id;

    public function __construct($slackId)
    {
        if (empty($slackId)) {
            throw new InvalidArgumentException('Id not set');
        }

        $this->id = $slackId;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->id;
    }
}
