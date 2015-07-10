<?php

namespace BlockCypher\AppWallet\Domain\Address;

use BlockCypher\AppWallet\Domain\Wallet\WalletId;

/**
 * Interface AddressRepository
 * @package BlockCypher\AppWallet\Domain\Address
 */
interface AddressRepository
{
    /**
     * @return AddressId
     */
    public function nextIdentity();

    /**
     * @param AddressId $addressId
     * @return Address
     */
    public function addressOfId(AddressId $addressId);

    /**
     * @param string $address
     * @param WalletId $walletId
     * @return Address
     */
    public function addressOfWalletId($address, WalletId $walletId);

    /**
     * @param WalletId $walletId
     * @return Address[]
     */
    public function addressesOfWalletId(WalletId $walletId);

    /**
     * @param Address $address
     */
    public function insert(Address $address);

    /**
     * @param Address[] $addresses
     */
    public function insertAll($addresses);

    /**
     * @param Address $address
     * @throws \Exception
     */
    public function update(Address $address);

    /**
     * @param Address[] $addresses
     */
    public function updateAll($addresses);

    /**
     * @param Address $address
     */
    public function delete(Address $address);

    /**
     * @param Address[] $addresses
     */
    public function deleteAll($addresses);

    /**
     * @param AddressSpecification $specification
     * @return Address[]
     */
    public function query($specification);

    /**
     * @return Address[]
     */
    public function findAll();
}