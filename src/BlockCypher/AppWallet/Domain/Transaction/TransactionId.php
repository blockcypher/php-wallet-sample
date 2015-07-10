<?php

namespace BlockCypher\AppWallet\Domain\Transaction;

/**
 * Class TransactionId
 * @package BlockCypher\AppWallet\Domain\Transaction
 */
class TransactionId
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
     * @return TransactionId
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
        $transactionId = new self(
            $entityAsArray['value']
        );

        return $transactionId;
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
     * @param TransactionId $transactionId
     * @return bool
     */
    public function equals(TransactionId $transactionId)
    {
        if ($this->value === $transactionId->getValue())
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