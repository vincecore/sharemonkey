<?php

namespace ShareMonkey\Document;

use Datetime;
use Doctrine\Common\Collections\ArrayCollection;
use ShareMonkey\Model\Slack\MessageId;

class Link
{
    private $id;

    /**
     * @var DateTime
     */
    private $createdTs;

    /**
     * @var string
     */
    private $slackId;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $url;

    /**
     * @var Tag[]
     */
    private $tags;

    /**
     * @var string
     */
    private $author;

    /**
     * @var User
     */
    private $user;

    /**
     * @var Like[]
     */
    private $likes;

    /**
     * @var Dislike[]
     */
    private $dislikes;

    /**
     * @var Comment[]
     */
    private $comments = array();

    /**
     * @var Click[]
     */
    private $clicks = array();

    private function __construct()
    {
        $this->tags = new ArrayCollection();
        $this->likes = new ArrayCollection();
        $this->dislikes = new ArrayCollection();
    }

    /**
     * @param $slackId
     * @param $title
     * @param $url
     * @param array $tags
     * @return Link
     */
    public static function fromSlack(
        MessageId $slackId,
        User $user,
        Datetime $createdTs,
        $title,
        $url,
        array $tags = array()
    ) {
        $link = new Link();
        $link->slackId = $slackId->getValue();
        $link->user = $user;
        $link->createdTs = $createdTs;
        $link->title = $title;
        $link->url = $url;
        $link->author = $user->getName();

        foreach ($tags as $tag) {
            $link->addTag(new Tag($tag));
        }

        return $link;
    }

    /**
     * @param Tag $tag
     */
    private function addTag(Tag $tag)
    {
        $this->tags[] = $tag;
    }

    /**
     * @param User $user
     */
    public function likedBy(User $user)
    {
        if ($this->hasUserAlreadyLiked($user)) {
            return;
        }

        $this->likes[] = Like::byUser($user);
    }

    /**
     * @param User $user
     */
    public function dislikedBy(User $user)
    {
        if ($this->hasUserAlreadyDisliked($user)) {
            return;
        }

        $this->dislikes[] = Dislike::byUser($user);
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return Tag[]|ArrayCollection
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @return Datetime
     */
    public function getCreatedTs()
    {
        return $this->createdTs;
    }

    /**
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @return ArrayCollection
     */
    public function getLikes()
    {
        return $this->likes;
    }

    /**
     * @return int
     */
    public function getNumberOfLikes()
    {
        return count($this->getLikes());
    }

    /**
     * @return ArrayCollection
     */
    public function getDislikes()
    {
        return $this->dislikes;
    }

    /**
     * @return int
     */
    public function getNumberOfDislikes()
    {
        return count($this->getDislikes());
    }

    /**
     * @return array
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * @return int
     */
    public function getNumberOfComments()
    {
        return count($this->getComments());
    }

    /**
     * @return array
     */
    public function getClicks()
    {
        return $this->clicks;
    }

    /**
     * @return int
     */
    public function getNumberOfClicks()
    {
        return count($this->getClicks());
    }

    /**
     * @return bool
     */
    private function hasUserAlreadyLiked(User $user)
    {
        foreach ($this->likes as $like) {
            if ($like->getUser()->equals($user)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return bool
     */
    private function hasUserAlreadyDisliked(User $user)
    {
        foreach ($this->dislikes as $dislike) {
            if ($dislike->getUser()->equals($user)) {
                return true;
            }
        }

        return false;
    }
}
