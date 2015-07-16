<?php

namespace BlockCypher\AppWallet\Infrastructure\Persistence\Flywheel;

use BlockCypher\AppCommon\Domain\User\UserId;
use BlockCypher\AppWallet\Domain\Wallet\EncryptedWallet;
use BlockCypher\AppWallet\Domain\Wallet\EncryptedWalletRepository;
use BlockCypher\AppWallet\Domain\Wallet\EncryptedWalletSpecification;
use BlockCypher\AppWallet\Domain\Wallet\Wallet;
use BlockCypher\AppWallet\Domain\Wallet\WalletId;
use BlockCypher\AppWallet\Infrastructure\Persistence\Flywheel\Document\EncryptedWalletDocument;
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

        $wallet = $this->documentToEncryptedWallet($result->first());

        return $wallet;
    }

    /**
     * @param EncryptedWalletDocument $walletDocument
     * @return EncryptedWallet
     */
    private function documentToEncryptedWallet($walletDocument)
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
     * @param UserId $userId
     * @return EncryptedWallet[]
     */
    public function walletsOfUserId(UserId $userId)
    {
        /** @var EncryptedWalletDocument[] $result */
        $result = $this->repository->query()
            ->where('userId', '==', $userId->getValue())
            ->execute();

        $encryptedWallets = $this->documentArrayToObjectArray($result);

        return $encryptedWallets;
    }

    /**
     * @param EncryptedWalletDocument[] $encryptedWalletDocuments
     * @return Wallet[]
     */
    private function documentArrayToObjectArray($encryptedWalletDocuments)
    {
        $encryptedWallets = array();
        foreach ($encryptedWalletDocuments as $encryptedWalletDocument) {
            $encryptedWallet = $this->documentToEncryptedWallet($encryptedWalletDocument);
            $encryptedWallets[] = $encryptedWallet;
        }
        return $encryptedWallets;
    }

    /**
     * @param EncryptedWallet $encryptedWallet
     */
    public function insert(EncryptedWallet $encryptedWallet)
    {
        $walletDocument = $this->walletToDocument($encryptedWallet);
        $this->repository->store($walletDocument);
    }

    /**
     * @param EncryptedWallet $encryptedWallet
     * @return EncryptedWalletDocument
     */
    private function walletToDocument(EncryptedWallet $encryptedWallet)
    {
        $searchFields = array(
            'id' => $encryptedWallet->getId()->getValue(),
            'userId' => $encryptedWallet->getUserId()->getValue(),
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
        /** @var EncryptedWalletDocument[] $result */
        $result = $this->repository->findAll();

        $wallets = $this->documentArrayToObjectArray($result);

        return $wallets;
    }
}