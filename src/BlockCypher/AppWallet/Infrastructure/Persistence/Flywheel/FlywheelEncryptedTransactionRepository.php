<?php

namespace BlockCypher\AppWallet\Infrastructure\Persistence\Flywheel;

use BlockCypher\AppWallet\Domain\Transaction\EncryptedTransaction;
use BlockCypher\AppWallet\Domain\Transaction\EncryptedTransactionRepository;
use BlockCypher\AppWallet\Domain\Transaction\EncryptedTransactionSpecification;
use BlockCypher\AppWallet\Domain\Transaction\Transaction;
use BlockCypher\AppWallet\Domain\Transaction\TransactionId;
use BlockCypher\AppWallet\Domain\Wallet\WalletId;
use BlockCypher\AppWallet\Infrastructure\Persistence\Flywheel\Document\EncryptedTransactionDocument;
use JamesMoss\Flywheel\Config;
use JamesMoss\Flywheel\Repository;
use JamesMoss\Flywheel\Result;

/**
 * Class FlywheelEncryptedTransactionRepository
 * @package BlockCypher\AppWallet\Infrastructure\Persistence\Flywheel
 */
class FlywheelEncryptedTransactionRepository implements EncryptedTransactionRepository
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
        $this->repository = new Repository('transactions', $config);
    }

    /**
     * @param TransactionId $transactionId
     * @return Transaction
     */
    public function transactionOfId(TransactionId $transactionId)
    {
        /** @var Result $result */
        $result = $this->repository->query()
            ->where('id', '==', $transactionId->getValue())
            ->execute();

        if ($result === false) {
            return null;
        }

        if ($result->count() == 0) {
            return null;
        }

        $encryptedTransaction = $this->documentToEncryptedTransaction($result->first());

        return $encryptedTransaction;
    }

    /**
     * @param EncryptedTransactionDocument $encryptedTransactionDocument
     * @return EncryptedTransaction
     */
    private function documentToEncryptedTransaction($encryptedTransactionDocument)
    {
        //DEBUG
        //var_dump($encryptedTransactionDocument);
        //die();

        /** @var EncryptedTransaction $encryptedTransaction */
        $encryptedTransaction = unserialize($encryptedTransactionDocument->data);

        //DEBUG
        //var_dump($encryptedTransaction);
        //die();

        return $encryptedTransaction;
    }

    /**
     * @param string $address
     * @param WalletId $walletId
     * @return EncryptedTransaction
     */
    public function transactionOfWalletId($address, WalletId $walletId)
    {
        // TODO: Code Review: Flywheel does no support multiple where conditions.
        // I have added indexes fields to metadata. It could be done with EncryptedTransactionSpecification
        // on in this method, getting first all wallet transactions and then filtering the transaction.
        // Really we could get the transaction searching only by transactions if we do not allow two wallets to have
        // the same transactions. For for the time being that's possible, although it seems not to be useful.
        // Maybe: two users importing the same address, one of them a watch only wallet and the other one
        // with spend permissions.
        // Or two users sharing a wallet but each of them with his own token.

        /** @var Result $result */
        $result = $this->repository->query()
            ->where('hash-walletId', '==', $address . '-' . $walletId->getValue())
            ->execute();

        if ($result === false) {
            return null;
        }

        if ($result->count() == 0) {
            return null;
        }

        $encryptedTransaction = $this->documentToEncryptedTransaction($result->first());

        return $encryptedTransaction;
    }

    /**
     * @param WalletId $walletId
     * @return EncryptedTransaction
     */
    public function transactionsOfWalletId(WalletId $walletId)
    {
        /** @var EncryptedTransactionDocument[] $result */
        $result = $this->repository->query()
            ->where('walletId', '==', $walletId->getValue())
            ->execute();

        $encryptedTransactions = $this->documentArrayToObjectArray($result);

        return $encryptedTransactions;
    }

    /**
     * @param EncryptedTransactionDocument[] $encryptedTransactionDocuments
     * @return EncryptedTransaction[]
     */
    private function documentArrayToObjectArray($encryptedTransactionDocuments)
    {
        $encryptedTransactions = array();
        foreach ($encryptedTransactionDocuments as $encryptedTransactionDocument) {
            $encryptedTransaction = $this->documentToEncryptedTransaction($encryptedTransactionDocument);
            $encryptedTransactions[] = $encryptedTransaction;
        }
        return $encryptedTransactions;
    }

    /**
     * @param EncryptedTransaction $encryptedTransaction
     * @throws \Exception
     */
    public function insert(EncryptedTransaction $encryptedTransaction)
    {
        $transactionDocument = $this->encryptedTransactionToDocument($encryptedTransaction);
        $this->repository->store($transactionDocument);
    }

    /**
     * @param EncryptedTransaction $encryptedTransaction
     * @return EncryptedTransactionDocument
     */
    private function encryptedTransactionToDocument(EncryptedTransaction $encryptedTransaction)
    {
        $searchFields = array(
            'id' => $encryptedTransaction->getId()->getValue(),
            'walletId' => $encryptedTransaction->getWalletId()->getValue(),
            'hash' => $encryptedTransaction->getHash(),
            'description' => $encryptedTransaction->getDescription(),
            'creationTime' => clone $encryptedTransaction->getCreationTime(),
            // Indexes
            // TODO: use a EncryptedTransactionSpecification
            'hash-walletId' => $encryptedTransaction->getHash() . '-' . $encryptedTransaction->getWalletId()->getValue(),
        );

        $docArray = $searchFields;
        $docArray['data'] = serialize($encryptedTransaction);

        $encryptedTransactionDocument = new EncryptedTransactionDocument($docArray);
        $encryptedTransactionDocument->setId($encryptedTransaction->getId()->getValue());

        return $encryptedTransactionDocument;
    }

    /**
     * @param EncryptedTransaction[] $encryptedTransactions
     * @throws \Exception
     */
    public function insertAll($encryptedTransactions)
    {
        // TODO: Implement insertAll() method.
        throw new \Exception('Not implemented');
    }

    /**
     * @param EncryptedTransaction $encryptedTransaction
     * @throws \Exception
     */
    public function update(EncryptedTransaction $encryptedTransaction)
    {
        // DEBUG
        //var_dump($encryptedTransaction);
        //die();

        $encryptedTransactionDocument = $this->encryptedTransactionToDocument($encryptedTransaction);

        // DEBUG
        //var_dump($encryptedTransactionDocument);
        //die();

        if (!$this->repository->update($encryptedTransactionDocument)) {
            // TODO: custom exception
            throw new \Exception("Error updating encrypted transaction repository");
        };

    }

    /**
     * @param EncryptedTransaction[] $encryptedTransactions
     * @throws \Exception
     */
    public function updateAll($encryptedTransactions)
    {
        // TODO: Implement updateAll() method.
        throw new \Exception('Not implemented');
    }

    /**
     * @param EncryptedTransaction $encryptedTransaction
     * @throws \Exception
     */
    public function delete(EncryptedTransaction $encryptedTransaction)
    {
        // TODO: Implement delete() method.
        throw new \Exception('Not implemented');
    }

    /**
     * @param EncryptedTransaction[] $encryptedTransactions
     * @throws \Exception
     */
    public function deleteAll($encryptedTransactions)
    {
        // TODO: Implement deleteAll() method.
        throw new \Exception('Not implemented');
    }

    /**
     * @param EncryptedTransactionSpecification $specification
     * @return Transaction[]
     * @throws \Exception
     */
    public function query($specification)
    {
        // TODO: Implement query() method.
        throw new \Exception('Not implemented');
    }

    /**
     * @return EncryptedTransaction[]
     */
    public function findAll()
    {
        /** @var EncryptedTransactionDocument[] $result */
        $result = $this->repository->findAll();

        $encryptedTransactions = $this->documentArrayToObjectArray($result);

        return $encryptedTransactions;
    }
}