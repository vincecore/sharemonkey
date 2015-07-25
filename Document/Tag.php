<?php

namespace ShareMonkey\Document;

use Assert\Assertion;

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
        Assertion::string($value);
        Assertion::minLength($value, 1);
        Assertion::maxLength($value, 50);

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
