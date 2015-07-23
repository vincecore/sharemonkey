<?php

namespace ShareMonkey\Repository;

use Doctrine\ODM\MongoDB\DocumentRepository;
use ShareMonkey\Document\Link;
use ShareMonkey\Document\Tag;
use ShareMonkey\Model\Slack\MessageId;

class LinkRepository extends DocumentRepository
{
    /**
     * @return Link[]
     */
    public function findForOverview(array $sort)
    {
        return $this->findBy(array(), $sort);
    }

    /**
     * @param MessageId $messageId
     * @return Link|null
     */
    public function findOneBySlackMessageId(MessageId $messageId)
    {
        return $this->findOneBy(array('slackId' => $messageId->getValue()));
    }

    /**
     * @param Tag $tag
     * @return Link[]
     */
    public function findByTag(Tag $tag)
    {
        return $this->findBy(array('tags.value' => $tag->getValue()));
    }
}
