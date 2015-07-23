<?php

namespace ShareMonkey\Service\Slack;

use Doctrine\Common\Persistence\ObjectManager;
use Embed\Embed;
use Psr\Log\LoggerInterface;
use ShareMonkey\Document\Link;
use ShareMonkey\Document\Tag;
use ShareMonkey\Document\User;
use ShareMonkey\Model\Slack\Message;
use ShareMonkey\Repository\UserRepository;

/**
 * Save links from messages where ShareMonkey is mentioned in
 */
class IncomingLinkProcessor implements MessageProcessor
{
    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var string
     */
    private $shareMonkeySlackId;

    /**
     * @param UserRepository $userRepository
     * @param ObjectManager $objectManager
     * @param LoggerInterface $logger
     */
    public function __construct(
        UserRepository $userRepository,
        ObjectManager $objectManager,
        $shareMonkeySlackId,
        LoggerInterface $logger
    ) {

        $this->objectManager = $objectManager;
        $this->logger = $logger;
        $this->userRepository = $userRepository;
        $this->shareMonkeySlackId = $shareMonkeySlackId;
    }

    /**
     * @param Message $message
     */
    public function process(Message $message)
    {
        // Only process messages where ShareMonkey is mentioned
        if ($this->shareMonkeyIsMentioned($message->getText()) === false) {
            return;
        }

        $urls = $this->findUrls($message->getText());
        $tags = $this->findTags($message->getText());

        if (count($urls) === 0) {
            $this->logger->debug('No urls found in message');
            return;
        }

        $user = $this->userRepository->findOneBySlackId($message->getUserId());
        if (!$user instanceof User) {
            $this->logger->error(sprintf('User "%s" not found', $message->getUserId()->getValue()));
            return;
        }

        foreach ($urls as $url) {
            $this->logger->debug(sprintf('processing url %s', $url));

            $info = Embed::create($url);

            $link = Link::fromSlack(
                $message->getId(),
                $user,
                $message->getCreatedAt(),
                ($info->getTitle() ?: $message->getText()),
                $url,
                $tags
            );

            $this->objectManager->persist($link);
            $this->objectManager->flush();
            $this->objectManager->clear();

            $this->logger->debug(sprintf('Saved link %s', $link->getUrl()));
        }
    }

    /**
     * @param string $text
     * @return bool
     */
    private function shareMonkeyIsMentioned($text)
    {
        $mentions = $this->findMentions($text);
        foreach ($mentions as $mention) {
            if ($mention == $this->shareMonkeySlackId) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param string $text
     * @return array
     */
    private function findMentions($text)
    {
        $pattern = '/<@(.*?)>/s';
        if (preg_match_all($pattern, $text, $matches)) {
            list(, $mentions) = ($matches);
            return $mentions;
        }

        return array();
    }

    /**
     * @param string $text
     * @return array
     */
    private function findUrls($text)
    {
        $pattern = '/<(.*?)>/s'; // Everything between <>
        if (preg_match_all($pattern, $text, $matches)) {
            $links = array();
            foreach ($matches[1] as $match) {
                if (strpos($match, 'http') !== false) {
                    $links[] = $match;
                }
            }
            return $links;
        }

        return array();
    }

    /**
     * @param string $text
     * @return array
     */
    private function findTags($text)
    {
        $pattern = '/#([a-z](-?[a-z0-9]+)*)/i';

        $tags = array();

        if (preg_match_all($pattern, $text, $matches)) {
            foreach ($matches[1] as $match) {
                $tags[] = new Tag($match);
            }
        }

        return $tags;
    }
}
