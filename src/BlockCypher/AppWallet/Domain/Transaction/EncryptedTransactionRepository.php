<?php

namespace BlockCypher\AppWallet\Domain\Transaction;

use BlockCypher\AppWallet\Domain\Wallet\WalletId;

/**
 * Interface EncryptedTransactionRepository
 * @package BlockCypher\AppWallet\Domain\Transaction
 */
interface EncryptedTransactionRepository
{
    /**
     * @param TransactionId $transactionId
     * @return EncryptedTransaction
     */
    public function transactionOfId(TransactionId $transactionId);

    /**
     * @param string $txHash
     * @param WalletId $walletId
     * @return EncryptedTransaction
     */
    public function transactionOfWalletId($txHash, WalletId $walletId);

    /**
     * @param WalletId $walletId
     * @return EncryptedTransaction[]
     */
    public function transactionsOfWalletId(WalletId $walletId);

    /**
     * @param EncryptedTransaction $transaction
     */
    public function insert(EncryptedTransaction $transaction);

    /**
     * @param EncryptedTransaction[] $transactions
     */
    public function insertAll($transactions);

    /**
     * @param EncryptedTransaction $transaction
     * @throws \Exception
     */
    public function update(EncryptedTransaction $transaction);

    /**
     * @param EncryptedTransaction[] $transactions
     */
    public function updateAll($transactions);

    /**
     * @param EncryptedTransaction $transaction
     */
    public function delete(EncryptedTransaction $transaction);

    /**
     * @param EncryptedTransaction[] $transactions
     */
    public function deleteAll($transactions);

    /**
     * @param EncryptedTransactionSpecification $specification
     * @return EncryptedTransaction[]
     */
    public function query($specification);

    /**
     * @return EncryptedTransaction[]
     */
    public function findAll();
}