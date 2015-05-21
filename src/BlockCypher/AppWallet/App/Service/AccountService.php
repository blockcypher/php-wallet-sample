<?php

namespace BlockCypher\AppWallet\App\Service;

use BlockCypher\AppWallet\Domain\Account\Account;

interface AccountService
{
    /**
     * @return Account[]
     */
    public function listAccounts();
}