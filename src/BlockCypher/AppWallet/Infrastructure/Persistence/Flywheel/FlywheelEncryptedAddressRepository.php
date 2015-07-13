<?php

namespace BlockCypher\AppWallet\Infrastructure\Persistence\Flywheel;

use BlockCypher\AppWallet\Domain\Address\Address;
use BlockCypher\AppWallet\Domain\Address\AddressId;
use BlockCypher\AppWallet\Domain\Address\EncryptedAddress;
use BlockCypher\AppWallet\Domain\Address\EncryptedAddressRepository;
use BlockCypher\AppWallet\Domain\Address\EncryptedAddressSpecification;
use BlockCypher\AppWallet\Domain\Wallet\WalletId;
use BlockCypher\AppWallet\Infrastructure\Persistence\Flywheel\Document\EncryptedAddressDocument;
use JamesMoss\Flywheel\Config;
use JamesMoss\Flywheel\Repository;
use JamesMoss\Flywheel\Result;

/**
 * Class FlywheelEncryptedAddressRepository
 * @package BlockCypher\AppWallet\Infrastructure\Persistence\Flywheel
 */
class FlywheelEncryptedAddressRepository implements EncryptedAddressRepository
{
    /**
     * @var Repository
     */
    private $repository;

    /**
     * Constructor
     * @param string $dataDir
     */
    public function __construct($dataDir)
    {
        $config = new Config($dataDir);
        $this->repository = new Repository('addresses', $config);
    }

    /**
     * @param AddressId $addressId
     * @return Address
     */
    public function addressOfId(AddressId $addressId)
    {
        /** @var Result $result */
        $result = $this->repository->query()
            ->where('id', '==', $addressId->getValue())
            ->execute();

        if ($result === false) {
            return null;
        }

        if ($result->count() == 0) {
            return null;
        }

        $encryptedAddress = $this->documentToEncryptedAddress($result->first());

        return $encryptedAddress;
    }

    /**
     * @param EncryptedAddressDocument $encryptedAddressDocument
     * @return EncryptedAddress
     */
    private function documentToEncryptedAddress($encryptedAddressDocument)
    {
        //DEBUG
        //var_dump($encryptedAddressDocument);
        //die();

        /** @var EncryptedAddress $encryptedAddress */
        $encryptedAddress = unserialize($encryptedAddressDocument->data);

        //DEBUG
        //var_dump($encryptedAddress);
        //die();

        return $encryptedAddress;
    }

    /**
     * @param string $address
     * @return Address
     */
    public function addressOfAddress($address)
    {
        /** @var Result $result */
        $result = $this->repository->query()
            ->where('address', '==', $address)
            ->execute();

        if ($result === false) {
            return null;
        }

        if ($result->count() == 0) {
            return null;
        }

        $encryptedAddress = $this->documentToEncryptedAddress($result->first());

        return $encryptedAddress;
    }

    /**
     * @param string $address
     * @param WalletId $walletId
     * @return EncryptedAddress
     */
    public function addressOfWalletId($address, WalletId $walletId)
    {
        // TODO: Code Review: Flywheel does no support multiple where conditions.
        // I have added indexes fields to metadata. It could be done with EncryptedAddressSpecification
        // on in this method, getting first all wallet addresses and then filtering the address.
        // Really we could get the address searching only by address if we do not allow two wallets to have
        // the same address. For for the time being that's possible, although it seems not to be useful.
        // Maybe: two users importing the same address, one of them a watch only wallet and the other one
        // with spend permissions.
        // Or two users sharing a wallet but each of them with his own token.

        $result = $this->repository->query()
            ->where('address-walletId', '==', $address . '-' . $walletId->getValue())
            ->execute();

        if ($result === false) {
            return null;
        }

        if ($result->count() == 0) {
            return null;
        }

        $encryptedAddress = $this->documentToEncryptedAddress($result->first());

        return $encryptedAddress;
    }

    /**
     * @param WalletId $walletId
     * @return EncryptedAddress[]
     */
    public function addressesOfWalletId(WalletId $walletId)
    {
        $result = $this->repository->query()
            ->where('walletId', '==', $walletId->getValue())
            ->execute();

        if ($result === false) {
            return array();
        }

        if ($result->count() == 0) {
            return array();
        }

        /** @var EncryptedAddressDocument[] $encryptedAddressDocuments */
        $encryptedAddressDocuments = $result;

        $encryptedAddresses = $this->documentArrayToObjectArray($encryptedAddressDocuments);

        return $encryptedAddresses;
    }

    /**
     * @param EncryptedAddressDocument[] $encryptedAddressDocuments
     * @return EncryptedAddress[]
     */
    private function documentArrayToObjectArray($encryptedAddressDocuments)
    {
        $encryptedAddresses = array();
        foreach ($encryptedAddressDocuments as $encryptedAddressDocument) {
            $encryptedAddress = $this->documentToEncryptedAddress($encryptedAddressDocument);
            $encryptedAddresses[] = $encryptedAddress;
        }
        return $encryptedAddresses;
    }

    /**
     * @param EncryptedAddress $encryptedAddress
     * @throws \Exception
     */
    public function insert(EncryptedAddress $encryptedAddress)
    {
        $addressDocument = $this->encryptedAddressToDocument($encryptedAddress);
        $this->repository->store($addressDocument);
    }

    /**
     * @param EncryptedAddress $encryptedAddress
     * @return EncryptedAddressDocument
     */
    private function encryptedAddressToDocument(EncryptedAddress $encryptedAddress)
    {
        $searchFields = array(
            'id' => $encryptedAddress->getId()->getValue(),
            'walletId' => $encryptedAddress->getWalletId()->getValue(),
            'address' => $encryptedAddress->getAddress(),
            'tag' => $encryptedAddress->getTag(),
            'creationTime' => clone $encryptedAddress->getCreationTime(),
            // Indexes
            // TODO: use a EncryptedAddressSpecification
            'address-walletId' => $encryptedAddress->getAddress() . '-' . $encryptedAddress->getWalletId()->getValue(),
        );

        $docArray = $searchFields;
        $docArray['data'] = serialize($encryptedAddress);

        $encryptedAddressDocument = new EncryptedAddressDocument($docArray);
        $encryptedAddressDocument->setId($encryptedAddress->getId()->getValue());

        return $encryptedAddressDocument;
    }

    /**
     * @param EncryptedAddress[] $encryptedAddresses
     * @throws \Exception
     */
    public function insertAll($encryptedAddresses)
    {
        // TODO: Implement insertAll() method.
        throw new \Exception('Not implemented');
    }

    /**
     * @param EncryptedAddress $encryptedAddress
     * @throws \Exception
     */
    public function update(EncryptedAddress $encryptedAddress)
    {
        // DEBUG
        //var_dump($encryptedAddress);
        //die();

        $encryptedAddressDocument = $this->encryptedAddressToDocument($encryptedAddress);

        // DEBUG
        //var_dump($encryptedAddressDocument);
        //die();

        if (!$this->repository->update($encryptedAddressDocument)) {
            // TODO: custom exception
            throw new \Exception("Error updating encrypted address repository");
        };

    }

    /**
     * @param EncryptedAddress[] $encryptedAddresses
     * @throws \Exception
     */
    public function updateAll($encryptedAddresses)
    {
        // TODO: Implement updateAll() method.
        throw new \Exception('Not implemented');
    }

    /**
     * @param EncryptedAddress $encryptedAddress
     * @throws \Exception
     */
    public function delete(EncryptedAddress $encryptedAddress)
    {
        // TODO: Implement delete() method.
        throw new \Exception('Not implemented');
    }

    /**
     * @param EncryptedAddress[] $encryptedAddresses
     * @throws \Exception
     */
    public function deleteAll($encryptedAddresses)
    {
        // TODO: Implement deleteAll() method.
        throw new \Exception('Not implemented');
    }

    /**
     * @param EncryptedAddressSpecification $specification
     * @return Address[]
     * @throws \Exception
     */
    public function query($specification)
    {
        // TODO: Implement query() method.
        throw new \Exception('Not implemented');
    }

    /**
     * @return EncryptedAddress[]
     */
    public function findAll()
    {
        $result = $this->repository->findAll();

        $encryptedAddresses = $this->documentArrayToObjectArray($result);

        return $encryptedAddresses;
    }
}