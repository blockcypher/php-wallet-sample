<?php

namespace BlockCypher\AppWallet\Domain\Address;

use BlockCypher\AppWallet\Domain\Wallet\WalletId;

/**
 * Interface EncryptedAddressRepository
 * @package BlockCypher\AppWallet\Domain\Address
 */
interface EncryptedAddressRepository
{
    /**
     * @param AddressId $addressId
     * @return EncryptedAddress
     */
    public function addressOfId(AddressId $addressId);

    /**
     * @param string $address
     * @return EncryptedAddress
     */
    public function addressOfAddress($address);

    /**
     * @param string $address
     * @param WalletId $walletId
     * @return EncryptedAddress
     */
    public function addressOfWalletId($address, WalletId $walletId);

    /**
     * @param WalletId $walletId
     * @return EncryptedAddress[]
     */
    public function addressesOfWalletId(WalletId $walletId);

    /**
     * @param EncryptedAddress $address
     */
    public function insert(EncryptedAddress $address);

    /**
     * @param EncryptedAddress[] $addresses
     */
    public function insertAll($addresses);

    /**
     * @param EncryptedAddress $address
     * @throws \Exception
     */
    public function update(EncryptedAddress $address);

    /**
     * @param EncryptedAddress[] $addresses
     */
    public function updateAll($addresses);

    /**
     * @param EncryptedAddress $address
     */
    public function delete(EncryptedAddress $address);

    /**
     * @param EncryptedAddress[] $addresses
     */
    public function deleteAll($addresses);

    /**
     * @param EncryptedAddressSpecification $specification
     * @return EncryptedAddress[]
     */
    public function query($specification);

    /**
     * @return EncryptedAddress[]
     */
    public function findAll();
}