<?php

namespace BlockCypher\AppWallet\Domain\Address;

/**
 * Interface EncryptedAddressSpecification. Used for In memory repositories.
 * @package BlockCypher\AppWallet\Domain\Address
 */
interface EncryptedAddressSpecification
{
    /**
     * @param Address $address
     * @return bool
     */
    public function specifies(Address $address);
}