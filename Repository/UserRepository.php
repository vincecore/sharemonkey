<?php

namespace ShareMonkey\Repository;

use Doctrine\ODM\MongoDB\DocumentRepository;
use ShareMonkey\Document\User;
use ShareMonkey\Model\Slack\UserId;

class UserRepository extends DocumentRepository
{
    /**
     * @param UserId $userId
     * @return User|null
     */
    public function findOneBySlackId(UserId $userId)
    {
        return $this->findOneBy(array('slackId' => $userId->getValue()));
    }
}
