<?php

namespace BlockCypher\AppWallet\Infrastructure\Persistence\Flywheel;

use BlockCypher\AppWallet\Domain\Wallet\EncryptedWallet;
use BlockCypher\AppWallet\Domain\Wallet\EncryptedWalletRepository;
use BlockCypher\AppWallet\Domain\Wallet\EncryptedWalletSpecification;
use BlockCypher\AppWallet\Domain\Wallet\Wallet;
use BlockCypher\AppWallet\Domain\Wallet\WalletId;
use JamesMoss\Flywheel\Config;
use JamesMoss\Flywheel\Document;
use JamesMoss\Flywheel\Repository;
use JamesMoss\Flywheel\Result;

/**
 * Class FlywheelEncryptedWalletRepository
 * @package BlockCypher\AppWallet\Infrastructure\Persistence\Flywheel
 */
class FlywheelEncryptedWalletRepository implements EncryptedWalletRepository
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
        $this->repository = new Repository('wallets', $config);
    }

    /**
     * @param WalletId $walletId
     * @return Wallet
     */
    public function walletOfId(WalletId $walletId)
    {
        /** @var Result $result */
        $result = $this->repository->query()
            ->where('id', '==', $walletId->getValue())
            ->execute();

        if ($result === false) {
            return null;
        }

        if ($result->count() == 0) {
            return null;
        }

        $wallet = $this->documentToWallet($result->first());

        return $wallet;
    }

    /**
     * @param $walletDocument
     * @return Wallet
     */
    private function documentToWallet($walletDocument)
    {
        //DEBUG
        //var_dump($walletDocument);
        //die();

        /** @var EncryptedWallet $encryptedWallet */
        $encryptedWallet = unserialize($walletDocument->data);

        //DEBUG
        //var_dump($wallet);
        //die();

        return $encryptedWallet;
    }

    /**
     * @param EncryptedWallet $wallet
     */
    public function insert(EncryptedWallet $wallet)
    {
        $walletDocument = $this->walletToDocument($wallet);
        $this->repository->store($walletDocument);
    }

    /**
     * @param EncryptedWallet $encryptedWallet
     * @return Document
     */
    private function walletToDocument(EncryptedWallet $encryptedWallet)
    {
        $searchFields = array(
            'id' => $encryptedWallet->getId()->getValue(),
            'name' => $encryptedWallet->getName(),
            'coinSymbol' => $encryptedWallet->getCoinSymbol(),
            'creationTime' => clone $encryptedWallet->getCreationTime(),
        );

        $docArray = $searchFields;
        $docArray['data'] = serialize($encryptedWallet);

        $walletDocument = new Document($docArray);
        $walletDocument->setId($encryptedWallet->getId()->getValue());

        // DEBUG
        //var_dump($wallet);
        //var_dump($walletDocument);
        //die();

        return $walletDocument;
    }

    /**
     * @param EncryptedWallet[] $wallets
     * @throws \Exception
     */
    public function insertAll($wallets)
    {
        // TODO: Implement insertAll() method.
        throw new \Exception('Not implemented');
    }

    /**
     * @param EncryptedWallet $wallet
     * @throws \Exception
     */
    public function update(EncryptedWallet $wallet)
    {
        $walletDocument = $this->walletToDocument($wallet);
        if (!$this->repository->update($walletDocument)) {
            // TODO: custom exception
            throw new \Exception("Error updating wallet repository");
        };

    }

    /**
     * @param EncryptedWallet[] $wallets
     * @throws \Exception
     */
    public function updateAll($wallets)
    {
        // TODO: Implement updateAll() method.
        throw new \Exception('Not implemented');
    }

    /**
     * @param EncryptedWallet $wallet
     * @throws \Exception
     */
    public function delete(EncryptedWallet $wallet)
    {
        // TODO: Implement delete() method.
        throw new \Exception('Not implemented');
    }

    /**
     * @param EncryptedWallet[] $wallets
     * @throws \Exception
     */
    public function deleteAll($wallets)
    {
        // TODO: Implement deleteAll() method.
        throw new \Exception('Not implemented');
    }

    /**
     * @param EncryptedWalletSpecification $specification
     * @return Wallet[]
     * @throws \Exception
     */
    public function query($specification)
    {
        // TODO: Implement query() method.
        throw new \Exception('Not implemented');
    }

    /**
     * @return Wallet[]
     */
    public function findAll()
    {
        /** @var Document[] $result */
        $result = $this->repository->findAll();

        $wallets = $this->documentArrayToWalletArray($result);

        return $wallets;
    }

    /**
     * @param Document[] $result
     * @return Wallet[]
     */
    private function documentArrayToWalletArray($result)
    {
        $wallets = array();
        foreach ($result as $walletDocument) {
            $wallet = $this->documentToWallet($walletDocument);
            $wallets[] = $wallet;
        }
        return $wallets;
    }
}