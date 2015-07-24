<?php

namespace ShareMonkey\Repository;

use Doctrine\ODM\MongoDB\DocumentRepository;
use ShareMonkey\Document\Link;
use ShareMonkey\Document\Tag;
use ShareMonkey\Model\Slack\MessageId;

class LinkRepository extends DocumentRepository
{
    const SORT_RECENT = 'recent';
    const SORT_TOP = 'top';

    /**
     * @return Link[]
     */
    public function findForOverview($sort)
    {
        return $this->findBy(array(), $this->getSort($sort));
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
    public function findByTag(Tag $tag, $sort)
    {
        return $this->findBy(array('tags.value' => $tag->getValue()), $this->getSort($sort));
    }

    /**
     * @param $sort
     * @return array
     */
    private function getSort($sort)
    {
        switch ($sort) {
            case self::SORT_TOP:
                return array(
                    'score' => '1',
                );
                break;
        }

        return array(
            'createdTs' => '-1',
        );
    }
}
