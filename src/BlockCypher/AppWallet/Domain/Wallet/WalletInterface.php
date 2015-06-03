<?php

namespace BlockCypher\AppWallet\Domain\Wallet;

use BlockCypher\AppWallet\Domain\Account\AccountId;
use Money\BigMoney;
use Money\Currency;

/**
 * Class WalletInterface
 * @package BlockCypher\AppWallet\Domain\Wallet
 */
interface WalletInterface
{
    /**
     * Get id
     *
     * @return WalletId
     */
    public function id();

    /**
     * Wallet unique name.
     *
     * @return string
     */
    public function name();

    /**
     * Account which uses this wallet.
     *
     * @return AccountId
     */
    public function accountId();

    /**
     * Wallet creation time.
     *
     * @return \DateTime
     */
    public function creationTime();

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
}