<?php

namespace ShareMonkey\Document;

final class Tag
{
    /**
     * @var string
     */
    private $value;

    /**
     * @param string $value
     */
    public function __construct($value)
    {
        $this->value = strtolower($value);
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }
}
