<?php

namespace ShareMonkey\Document;

final class Like
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
        $like = new Like();
        $like->createdTs = new \DateTime();
        $like->user = $user;

        return $like;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }
}
