<?php

namespace ShareMonkey\Model;

use Assert\Assertion;

/**
 * Represent text from a message
 */
final class Text
{
    /**
     * @var string
     */
    private $text;

    /**
     * @var array
     */
    private $tags;

    /**
     * @var array
     */
    private $urls;

    /**
     * @var array
     */
    private $mentions;

    /**
     * @param $text
     */
    public function __construct($text)
    {
        Assertion::string($text);
        Assertion::minLength($text, 1);
        Assertion::maxLength($text, 1000);

        $this->text = $text;
    }

    /**
     * @return array
     */
    public function getTags()
    {
        if ($this->tags === null) {
            $this->tags = $this->findTags($this->text);
        }

        return $this->tags;
    }

    /**
     * @return array
     */
    public function getUrls()
    {
        if ($this->urls === null) {
            $this->urls = $this->findUrls($this->text);
        }

        return $this->urls;
    }

    /**
     * @return array
     */
    public function getMentions()
    {
        if ($this->mentions === null) {
            $this->mentions = $this->findMentions($this->text);
        }

        return $this->mentions;
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
                $tags[] = $match;
            }
        }

        return $tags;
    }
}
