<?php

namespace BlockCypher\AppWallet\Presentation\Facade\Internal;

use BlockCypher\AppWallet\App\Service\AccountService;
use BlockCypher\AppWallet\Domain\Account\Account;
use BlockCypher\AppWallet\Presentation\Facade\AccountServiceFacade;

class AccountServiceFacadeImpl implements AccountServiceFacade
{
    /**
     * @var AccountService
     */
    private $accountService;

    /**
     * @param AccountService $accountService
     */
    function __construct(AccountService $accountService)
    {
        $this->accountService = $accountService;
    }

    /**
     * @return array
     */
    public function listAccounts()
    {
        $accounts = $this->accountService->listAccounts();

        $accountDtos = Account::arrayToDtoArray($accounts);

        return $accountDtos;
    }
}