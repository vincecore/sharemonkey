<?php

namespace ShareMonkey\Document;

final class Comment
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

    /**
     * @var string
     */
    private $text;
}
