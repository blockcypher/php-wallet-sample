<?php

namespace BlockCypher\AppWallet\App\Service\Internal;

use BlockCypher\AppWallet\App\Service\AddressService;
use BlockCypher\AppWallet\Domain\Account\AccountId;
use BlockCypher\AppWallet\Domain\Address\Address;
use BlockCypher\AppWallet\Domain\Wallet\WalletRepository;

class AddressServiceImpl implements AddressService
{
    /**
     * @var WalletRepository
     */
    private $walletRepository;

    /**
     * Constructor
     * @param WalletRepository $walletRepository
     */
    public function __construct(
        WalletRepository $walletRepository
    )
    {
        $this->walletRepository = $walletRepository;
    }

    /**
     * @param AccountId $accountId
     * @return Address[]
     */
    public function listAccountAddresses(AccountId $accountId)
    {
        // TODO: Code Review. Get addresses from Account
        // $addresses = $this->accountRepository->accountOfId($accountId)->wallet()->getAddresses();
        // Accounts type can be BTC or EUR (not implemented). Only BTC accounts have bitcoin addresses
        // EUR account will have Bank Accounts.
        // In case we want load wallet from WalletRepository inside an Account method we could use this:
        // http://verraes.net/2011/05/lazy-loading-with-closures/
        $wallet = $this->walletRepository->walletOfAccountId($accountId);
        return $wallet->getAddresses();
    }
}