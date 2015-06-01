<?php

namespace BlockCypher\AppWallet\Domain\Account;

/**
 * Interface EncryptedAccountSpecification.  Used for In memory repositories.
 * @package BlockCypher\AppWallet\Domain\Account
 */
interface EncryptedAccountSpecification
{
    /**
     * @param Account $account
     * @return bool
     */
    public function specifies(Account $account);
}