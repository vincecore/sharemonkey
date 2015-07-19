<?php

namespace ShareMonkey\Document;

final class Dislike
{
    private $id;

    /**
     * @var \DateTime
     */
    private $createdTs;

    /**
     * @var User
     */
    private $user;

    private function __construct()
    {
    }

    /**
     * @param User $user
     * @return Like
     */
    public static function byUser(User $user)
    {
        $dislike = new DisLike();
        $dislike->createdTs = new \DateTime();
        $dislike->user = $user;

        return $dislike;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }
}
