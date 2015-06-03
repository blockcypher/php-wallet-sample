<?php

namespace BlockCypher\AppWallet\Domain\Account;

use BlockCypher\AppCommon\App\Service\Clock;
use BlockCypher\AppCommon\App\Service\WalletService;
use BlockCypher\AppCommon\Domain\BigMoney;
use BlockCypher\AppWallet\Domain\Wallet\FiatWalletRepository;
use BlockCypher\AppWallet\Domain\Wallet\WalletInterface;
use BlockCypher\AppWallet\Domain\Wallet\WalletRepository;
use Money\Currency;

interface AccountInterface
{
    /**
     * Get id
     *
     * @return AccountId
     */
    public function id();

    /**
     * Get account type. AccountType enum
     *
     * @return string
     */
    public function type();

    /**
     * Account creation time
     *
     * @return \DateTime
     */
    public function creationTime();

    /**
     * Account tag
     * @return string
     */
    public function tag();

    /**
     * Account wallet
     * @return WalletInterface
     */
    public function wallet();

    /**
     * Created the wallet associated to the account
     * @param WalletRepository $walletRepository
     * @param WalletService $walletService
     * @param Clock $clock
     */
    public function createCryptoWallet(
        WalletRepository $walletRepository,
        WalletService $walletService,
        Clock $clock
    );

    /**
     * Created the wallet associated to the account
     * @param FiatWalletRepository $fiatWalletRepository
     * @param Clock $clock
     */
    public function createFiatWallet(FiatWalletRepository $fiatWalletRepository, Clock $clock);

    /**
     * @return BigMoney
     * @throws \Exception
     */
    public function balance();

    /**
     * @return Currency
     * @throws \Exception
     */
    public function currency();

    /**
     * @param string $newTag
     */
    public function changeTag($newTag);

    /**
     * TODO: is this only a repository method?
     */
    public function delete();

    /**
     * Set this account as primary
     * TODO: should be a user method: $user->setPrimaryAccount($account) ?
     * @param $user
     */
    public function setAsPrimary($user);

    /**
     * @param BigMoney $amount
     * @param \DateTime $date
     * @return
     */
    public function deposit(BigMoney $amount, \DateTime $date);

    /**
     * @param BigMoney $amount
     * @param \DateTime $date
     * @return mixed
     */
    public function withdrawal(BigMoney $amount, \DateTime $date);

    /**
     * Transfer funds from this account to another account of the same currency.
     * @param AccountInterface $account
     * @param BigMoney $amount
     */
    public function transferFundsTo(AccountInterface $account, BigMoney $amount);
}