<?php

namespace BlockCypher\AppWallet\Domain\Wallet;

use BlockCypher\AppCommon\Domain\Model;
use BlockCypher\AppWallet\Domain\Account\AccountId;
use Money\BigMoney;
use Money\Currency;

/**
 * Class FiatWallet
 * @package BlockCypher\AppWallet\Domain\Wallet
 */
class FiatWallet extends Model implements WalletInterface
{
    /**
     * Get id
     *
     * @return WalletId
     */
    public function id()
    {
        // TODO: Implement id() method.
    }

    /**
     * Wallet unique name.
     *
     * @return string
     */
    public function name()
    {
        // TODO: Implement name() method.
    }

    /**
     * Account which uses this wallet.
     *
     * @return AccountId
     */
    public function accountId()
    {
        // TODO: Implement accountId() method.
    }

    /**
     * Wallet creation time.
     *
     * @return \DateTime
     */
    public function creationTime()
    {
        // TODO: Implement creationTime() method.
    }

    /**
     * @return BigMoney
     * @throws \Exception
     */
    public function balance()
    {
        // TODO: Implement balance() method.
    }

    /**
     * @return Currency
     * @throws \Exception
     */
    public function currency()
    {
        // TODO: Implement currency() method.
    }
}