<?php

namespace BlockCypher\AppWallet\Domain\Address;

/**
 * Class AddressId
 * @package BlockCypher\AppWallet\Domain\Address
 */
class AddressId
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
     * @return AddressId
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
        $addressId = new self(
            $entityAsArray['value']
        );

        return $addressId;
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
     * @param AddressId $addressId
     * @return bool
     */
    public function equals(AddressId $addressId)
    {
        if ($this->value === $addressId->getValue())
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