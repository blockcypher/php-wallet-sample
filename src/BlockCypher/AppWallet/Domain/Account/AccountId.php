<?php

namespace BlockCypher\AppWallet\Domain\Account;

/**
 * Class AccountId
 * @package BlockCypher\AppWallet\Domain\Account
 */
class AccountId
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
     * @return AccountId
     */
    public static function create($value)
    {
        return new self($value);
    }

    /**
     * @param array $entityAsArray
     * @return AccountId
     */
    public static function fromArray($entityAsArray)
    {
        $account = new self(
            $entityAsArray['value']
        );

        return $account;
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
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }
}