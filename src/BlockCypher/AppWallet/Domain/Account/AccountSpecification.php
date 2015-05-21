<?php

namespace BlockCypher\AppWallet\Domain\Account;

/**
 * Interface AccountSpecification.  Used for In memory repositories.
 * @package BlockCypher\AppWallet\Domain\Account
 */
interface AccountSpecification
{
    /**
     * @param Account $account
     * @return bool
     */
    public function specifies(Account $account);
}