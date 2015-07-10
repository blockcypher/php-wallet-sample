<?php

namespace BlockCypher\AppWallet\Infrastructure\Persistence\Flywheel;

use BlockCypher\AppCommon\App\Service\Decryptor;
use BlockCypher\AppCommon\App\Service\Encryptor;
use BlockCypher\AppWallet\Domain\Transaction\EncryptedTransaction;
use BlockCypher\AppWallet\Domain\Transaction\EncryptedTransactionRepository;
use BlockCypher\AppWallet\Domain\Transaction\Transaction;
use BlockCypher\AppWallet\Domain\Transaction\TransactionId;
use BlockCypher\AppWallet\Domain\Transaction\TransactionRepository;
use BlockCypher\AppWallet\Domain\Transaction\TransactionSpecification;
use BlockCypher\AppWallet\Domain\Wallet\WalletId;

/**
 * Class FlywheelTransactionRepository
 * @package BlockCypher\AppWallet\Infrastructure\Persistence\Flywheel
 */
class FlywheelTransactionRepository implements TransactionRepository
{
    /**
     * @var EncryptedTransactionRepository
     */
    private $encryptedTransactionRepository;

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
     * @param EncryptedTransactionRepository $encryptedTransactionRepository
     * @param Encryptor $encryptor
     * @param Decryptor $decryptor
     */
    public function __construct(
        EncryptedTransactionRepository $encryptedTransactionRepository,
        Encryptor $encryptor,
        Decryptor $decryptor
    )
    {
        $this->encryptedTransactionRepository = $encryptedTransactionRepository;
        $this->encryptor = $encryptor;
        $this->decryptor = $decryptor;
    }

    /**
     * @return TransactionId
     * @throws \Exception
     */
    public function nextIdentity()
    {
        $id = strtoupper(str_replace('.', '', uniqid('', true)));

        return TransactionId::create($id);
    }

    /**
     * @param TransactionId $transactionId
     * @return Transaction
     */
    public function transactionOfId(TransactionId $transactionId)
    {
        $transaction = $this->encryptedTransactionRepository->transactionOfId($transactionId)->decryptUsing($this->decryptor);
        return $transaction;
    }

    /**
     * @param string $txHash
     * @param WalletId $walletId
     * @return Transaction
     */
    public function transactionOfWalletId($txHash, WalletId $walletId)
    {
        $encryptedTransaction = $this->encryptedTransactionRepository->transactionOfWalletId($txHash, $walletId);

        if ($encryptedTransaction === null) {
            return null;
        }

        $transaction = $encryptedTransaction->decryptUsing($this->decryptor);

        return $transaction;
    }

    /**
     * @param WalletId $walletId
     * @return Transaction[]
     */
    public function transactionsOfWalletId(WalletId $walletId)
    {
        $encryptedTransactions = $this->encryptedTransactionRepository->transactionsOfWalletId($walletId);
        $transactions = $this->decryptEncryptedTransactionArray($encryptedTransactions);
        return $transactions;
    }

    /**
     * @param EncryptedTransaction[] $encryptedTransactions
     * @return Transaction[]
     */
    private function decryptEncryptedTransactionArray($encryptedTransactions)
    {
        if ($encryptedTransactions === null)
            return null;

        $transactions = array();
        foreach ($encryptedTransactions as $encryptedTransaction) {
            $transactions[] = $encryptedTransaction->decryptUsing($this->decryptor);
        }
        return $transactions;
    }

    /**
     * @param Transaction $transaction
     */
    public function insert(Transaction $transaction)
    {
        $this->encryptedTransactionRepository->insert($transaction->encryptUsing($this->encryptor));
    }

    /**
     * @param Transaction[] $transactions
     * @throws \Exception
     */
    public function insertAll($transactions)
    {
        $this->encryptedTransactionRepository->insertAll($this->encryptTransactionArray($transactions));
    }

    /**
     * @param Transaction[] $transactions
     * @return array
     */
    private function encryptTransactionArray($transactions)
    {
        if ($transactions === null)
            return null;

        $encryptedTransactions = array();
        foreach ($transactions as $transaction) {
            $encryptedTransactions[] = $transaction->encryptUsing($this->encryptor);
        }
        return $encryptedTransactions;
    }

    /**
     * @param Transaction $transaction
     * @throws \Exception
     */
    public function update(Transaction $transaction)
    {
        // DEBUG
        //var_dump($transaction->encryptUsing($this->encryptor));
        //die();

        $this->encryptedTransactionRepository->update($transaction->encryptUsing($this->encryptor));
    }

    /**
     * @param Transaction[] $transactions
     * @throws \Exception
     */
    public function updateAll($transactions)
    {
        $this->encryptedTransactionRepository->updateAll($this->encryptTransactionArray($transactions));
    }

    /**
     * @param Transaction $transaction
     * @throws \Exception
     */
    public function delete(Transaction $transaction)
    {
        $this->encryptedTransactionRepository->delete($transaction->encryptUsing($this->encryptor));
    }

    /**
     * @param Transaction[] $transactions
     * @throws \Exception
     */
    public function deleteAll($transactions)
    {
        $this->encryptedTransactionRepository->deleteAll($this->encryptTransactionArray($transactions));
    }

    /**
     * @param TransactionSpecification $specification
     * @return Transaction[]
     * @throws \Exception
     */
    public function query($specification)
    {
        // TODO: Implement query() method.
        throw new \Exception('Not implemented');
    }

    /**
     * @return Transaction[]
     */
    public function findAll()
    {
        $encryptedTransactions = $this->encryptedTransactionRepository->findAll();

        $transactions = $this->decryptEncryptedTransactionArray($encryptedTransactions);

        return $transactions;
    }
}