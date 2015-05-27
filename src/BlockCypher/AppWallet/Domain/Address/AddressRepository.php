<?php

namespace BlockCypher\AppWallet\Domain\Address;

/**
 * Interface AddressRepository
 * @package BlockCypher\AppWallet\Domain\Address
 */
interface AddressRepository
{
    /**
     * @param string $address
     * @return Address
     */
    public function findAddress($address);

    /**
     * @param Address $account
     */
    public function insert(Address $account);

    /**
     * @param Address[] $accounts
     */
    public function insertAll($accounts);

    /**
     * @param Address $account
     */
    public function update(Address $account);

    /**
     * @param Address[] $accounts
     */
    public function updateAll($accounts);

    /**
     * @param Address $account
     */
    public function delete(Address $account);

    /**
     * @param Address[] $accounts
     */
    public function deleteAll($accounts);

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