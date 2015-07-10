<?php

namespace BlockCypher\AppWallet\Domain\Transaction;

/**
 * Interface TransactionSpecification.  Used for In memory repositories.
 * @package BlockCypher\AppWallet\Domain\Transaction
 */
interface TransactionSpecification
{
    /**
     * @param Transaction $transaction
     * @return bool
     */
    public function specifies(Transaction $transaction);
}