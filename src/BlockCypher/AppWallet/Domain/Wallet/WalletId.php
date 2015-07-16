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
     * @return $this
     */
    public static function fromArray($entityAsArray)
    {
        $walletId = new self(
            $entityAsArray['value']
        );

        return $walletId;
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
     * @param WalletId $walletId
     * @return bool
     */
    public function equals(WalletId $walletId)
    {
        if ($this->value === $walletId->getValue())
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