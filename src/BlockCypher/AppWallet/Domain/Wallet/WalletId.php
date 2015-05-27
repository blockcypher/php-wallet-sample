<?php

namespace BlockCypher\AppWallet\Domain\Wallet;

/**
 * Class WalletId
 * @package BlockCypher\AppWallet\Domain\Wallet
 */
class WalletId
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
     * @return WalletId
     */
    public static function create($value)
    {
        return new self($value);
    }

    /**
     * @param array $entityAsArray
     * @return WalletId
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