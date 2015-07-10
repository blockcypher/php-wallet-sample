<?php

namespace BlockCypher\AppWallet\Domain\Transaction;

/**
 * Interface EncryptedTransactionSpecification.  Used for In memory repositories.
 * @package BlockCypher\AppWallet\Domain\Transaction
 */
interface EncryptedTransactionSpecification
{
    /**
     * @param Transaction $transaction
     * @return bool
     */
    public function specifies(Transaction $transaction);
}