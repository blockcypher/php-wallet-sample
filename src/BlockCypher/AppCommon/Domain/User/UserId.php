<?php

namespace BlockCypher\AppCommon\Domain\User;

/**
 * Class UserId
 * @package BlockCypher\AppCommon\Domain\User
 */
class UserId
{
    /**
     * @var string
     */
    private $value;

    /**
     * @param $value
     */
    public function __construct($value)
    {
        $this->value = (string)$value;
    }

    /**
     * @param $value
     * @return UserId
     */
    public static function create($value)
    {
        return new self($value);
    }

    /**
     * @param array $entityAsArray
     * @return $this
     */
    public static function fromArray($entityAsArray)
    {
        $userId = new self(
            $entityAsArray['value']
        );

        return $userId;
    }

    public function toArray()
    {
        $entityAsArray = array();
        $entityAsArray['value'] = $this->value;
        return $entityAsArray;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->value;
    }

    /**
     * @param UserId $userId
     * @return bool
     */
    public function equals(UserId $userId)
    {
        if ($this->value === $userId->getValue())
            return true;
        else
            return false;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }
}