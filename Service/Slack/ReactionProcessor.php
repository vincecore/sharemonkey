<?php

namespace ShareMonkey\Service\Slack;

use Doctrine\Common\Persistence\ObjectManager;
use Psr\Log\LoggerInterface;
use ShareMonkey\Document\Link;
use ShareMonkey\Document\User;
use ShareMonkey\Model\Slack\Reaction;
use ShareMonkey\Repository\LinkRepository;
use ShareMonkey\Repository\UserRepository;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Like / dislike links by processing reaction on slack messages
 */
class ReactionProcessor
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var LinkRepository
     */
    private $linkRepository;

    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @param UserRepository $userRepository
     * @param ObjectManager $objectManager
     * @param LoggerInterface $logger
     */
    public function __construct(
        UserRepository $userRepository,
        LinkRepository $linkRepository,
        ObjectManager $objectManager,
        LoggerInterface $logger,
        EventDispatcherInterface $eventDispatcher
    ) {

        $this->objectManager = $objectManager;
        $this->logger = $logger;
        $this->userRepository = $userRepository;
        $this->linkRepository = $linkRepository;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @param Reaction $reaction
     */
    public function process(Reaction $reaction)
    {
        // Only process reaction on saved links
        $link = $this->linkRepository->findOneBySlackMessageId($reaction->getMessageId());
        if (!$link instanceof Link) {
            return;
        }

        $user = $this->userRepository->findOneBySlackId($reaction->getUserId());
        if (!$user instanceof User) {
            $this->logger->error(sprintf('User "%s" not found', $reaction->getUserId()));
            return;
        }

        if ($reaction->isLike()) {
            $link->likedBy($user);
        }

        if ($reaction->isDislike()) {
            $link->dislikedBy($user);
        }

        $this->objectManager->flush();
        $this->objectManager->clear();
    }
}
