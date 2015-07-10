<?php

namespace BlockCypher\AppWallet\Infrastructure\Persistence\Flywheel;

use BlockCypher\AppCommon\App\Service\Decryptor;
use BlockCypher\AppCommon\App\Service\Encryptor;
use BlockCypher\AppWallet\Domain\Address\Address;
use BlockCypher\AppWallet\Domain\Address\AddressId;
use BlockCypher\AppWallet\Domain\Address\AddressRepository;
use BlockCypher\AppWallet\Domain\Address\AddressSpecification;
use BlockCypher\AppWallet\Domain\Address\EncryptedAddress;
use BlockCypher\AppWallet\Domain\Address\EncryptedAddressRepository;
use BlockCypher\AppWallet\Domain\Wallet\WalletId;

/**
 * Class FlywheelAddressRepository
 * @package BlockCypher\AppWallet\Infrastructure\Persistence\Flywheel
 */
class FlywheelAddressRepository implements AddressRepository
{
    /**
     * @var EncryptedAddressRepository
     */
    private $encryptedAddressRepository;

    /**
     * @var Encryptor
     */
    private $encryptor;

    /**
     * @var Decryptor
     */
    private $decryptor;

    /**
     * Constructor
     * @param EncryptedAddressRepository $encryptedAddressRepository
     * @param Encryptor $encryptor
     * @param Decryptor $decryptor
     */
    public function __construct(
        EncryptedAddressRepository $encryptedAddressRepository,
        Encryptor $encryptor,
        Decryptor $decryptor
    )
    {
        $this->encryptedAddressRepository = $encryptedAddressRepository;
        $this->encryptor = $encryptor;
        $this->decryptor = $decryptor;
    }

    /**
     * @return AddressId
     * @throws \Exception
     */
    public function nextIdentity()
    {
        $id = strtoupper(str_replace('.', '', uniqid('', true)));

        return AddressId::create($id);
    }

    /**
     * @param AddressId $addressId
     * @return Address
     */
    public function addressOfId(AddressId $addressId)
    {
        $address = $this->encryptedAddressRepository->addressOfId($addressId)->decryptUsing($this->decryptor);
        return $address;
    }

    /**
     * @param string $address
     * @param WalletId $walletId
     * @return Address
     */
    public function addressOfWalletId($address, WalletId $walletId)
    {
        // DEBUG
        //var_dump($address);
        //var_dump($walletId);
        //die();

        $encryptedAddress = $this->encryptedAddressRepository->addressOfWalletId($address, $walletId);

        // DEBUG
        //var_dump($encryptedAddress);
        //die();

        if ($encryptedAddress === null) {
            return null;
        }

        $address = $encryptedAddress->decryptUsing($this->decryptor);

        return $address;
    }

    /**
     * @param WalletId $walletId
     * @return Address[]
     */
    public function addressesOfWalletId(WalletId $walletId)
    {
        $encryptedAddresses = $this->encryptedAddressRepository->addressesOfWalletId($walletId);
        $addresses = $this->decryptEncryptedAddressArray($encryptedAddresses);
        return $addresses;
    }

    /**
     * @param EncryptedAddress[] $encryptedAddresses
     * @return Address[]
     */
    private function decryptEncryptedAddressArray($encryptedAddresses)
    {
        // DEBUG
        //var_dump($encryptedAddresses);
        //die();

        if ($encryptedAddresses === null)
            return null;

        $addresses = array();
        foreach ($encryptedAddresses as $encryptedAddress) {
            $addresses[] = $encryptedAddress->decryptUsing($this->decryptor);
        }
        return $addresses;
    }

    /**
     * @param Address $address
     */
    public function insert(Address $address)
    {
        $this->encryptedAddressRepository->insert($address->encryptUsing($this->encryptor));
    }

    /**
     * @param Address[] $addresses
     * @throws \Exception
     */
    public function insertAll($addresses)
    {
        $this->encryptedAddressRepository->insertAll($this->encryptAddressArray($addresses));
    }

    /**
     * @param Address[] $addresses
     * @return array
     */
    private function encryptAddressArray($addresses)
    {
        if ($addresses === null)
            return null;

        $encryptedAddresses = array();
        foreach ($addresses as $address) {
            $encryptedAddresses[] = $address->encryptUsing($this->encryptor);
        }
        return $encryptedAddresses;
    }

    /**
     * @param Address $address
     * @throws \Exception
     */
    public function update(Address $address)
    {
        // DEBUG
        //var_dump($address->encryptUsing($this->encryptor));
        //die();

        $this->encryptedAddressRepository->update($address->encryptUsing($this->encryptor));
    }

    /**
     * @param Address[] $addresses
     * @throws \Exception
     */
    public function updateAll($addresses)
    {
        $this->encryptedAddressRepository->updateAll($this->encryptAddressArray($addresses));
    }

    /**
     * @param Address $address
     * @throws \Exception
     */
    public function delete(Address $address)
    {
        $this->encryptedAddressRepository->delete($address->encryptUsing($this->encryptor));
    }

    /**
     * @param Address[] $addresses
     * @throws \Exception
     */
    public function deleteAll($addresses)
    {
        $this->encryptedAddressRepository->deleteAll($this->encryptAddressArray($addresses));
    }

    /**
     * @param AddressSpecification $specification
     * @return Address[]
     * @throws \Exception
     */
    public function query($specification)
    {
        // TODO: Implement query() method.
        throw new \Exception('Not implemented');
    }

    /**
     * @return Address[]
     */
    public function findAll()
    {
        $encryptedAddresses = $this->encryptedAddressRepository->findAll();

        $addresses = $this->decryptEncryptedAddressArray($encryptedAddresses);

        return $addresses;
    }
}