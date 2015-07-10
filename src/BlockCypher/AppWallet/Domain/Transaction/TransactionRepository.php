<?php

namespace BlockCypher\AppWallet\Domain\Transaction;

use BlockCypher\AppWallet\Domain\Wallet\WalletId;

/**
 * Interface TransactionRepository
 * @package BlockCypher\AppWallet\Domain\Transaction
 */
interface TransactionRepository
{
    /**
     * @return TransactionId
     */
    public function nextIdentity();

    /**
     * @param TransactionId $transactionId
     * @return Transaction
     */
    public function transactionOfId(TransactionId $transactionId);

    /**
     * @param string $txHash
     * @param WalletId $walletId
     * @return Transaction
     */
    public function transactionOfWalletId($txHash, WalletId $walletId);

    /**
     * @param WalletId $walletId
     * @return Transaction[]
     */
    public function transactionsOfWalletId(WalletId $walletId);

    /**
     * @param Transaction $transaction
     */
    public function insert(Transaction $transaction);

    /**
     * @param Transaction[] $transactions
     */
    public function insertAll($transactions);

    /**
     * @param Transaction $transaction
     * @throws \Exception
     */
    public function update(Transaction $transaction);

    /**
     * @param Transaction[] $transactions
     */
    public function updateAll($transactions);

    /**
     * @param Transaction $transaction
     */
    public function delete(Transaction $transaction);

    /**
     * @param Transaction[] $transactions
     */
    public function deleteAll($transactions);

    /**
     * @param TransactionSpecification $specification
     * @return Transaction[]
     */
    public function query($specification);

    /**
     * @return Transaction[]
     */
    public function findAll();
}