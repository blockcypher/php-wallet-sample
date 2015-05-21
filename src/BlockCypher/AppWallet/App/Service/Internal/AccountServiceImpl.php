<?php

namespace BlockCypher\AppWallet\App\Service\Internal;

use BlockCypher\AppWallet\App\Service\AccountService;
use BlockCypher\AppWallet\Domain\Account\Account;
use BlockCypher\AppWallet\Domain\Account\AccountRepository;

class AccountServiceImpl implements AccountService
{
    /**
     * @var AccountRepository
     */
    private $accountRepository;

    /**
     * Constructor
     * @param AccountRepository $accountRepository
     */
    public function __construct(AccountRepository $accountRepository)
    {
        $this->accountRepository = $accountRepository;
    }

    /**
     * @return Account[]
     */
    public function listAccounts()
    {
        $accounts = $this->accountRepository->findAll();
        return $accounts;
    }
}