<?php

namespace ShareMonkey\Document;

use ShareMonkey\Model\Slack\UserId;

class User
{
    private $id;

    /**
     * @var string
     */
    private $slackId;

    /**
     * @var string
     */
    private $name;


    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $image;

    public function __construct()
    {
    }

    /**
     * @param $slackId
     * @param $title
     * @param $url
     * @return Link
     */
    public static function fromSlack(
        UserId $slackId,
        $name,
        $email,
        $image
    ) {
        $user = new User;
        $user->slackId = $slackId->getValue();
        $user->name = $name;
        $user->email = $email;
        $user->image = $image;

        return $user;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param User $user
     * @return bool
     */
    public function equals(User $user)
    {
        return $user->id === $this->id;
    }
}
