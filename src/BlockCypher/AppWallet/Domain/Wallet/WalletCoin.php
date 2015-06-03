<?php

namespace BlockCypher\AppWallet\Domain\Wallet;

use BlockCypher\AppCommon\Domain\Enum;
use Money\Currency;

/**
 * Class WalletCoin
 * @package BlockCypher\AppWallet\Domain\Wallet
 */
class WalletCoin extends Enum
{
    const BTC = 'btc';
    const BTC_TESTNET = 'btc-testnet';
    const LTC = 'ltc';
    const DOGE = 'doge';
    const URO = 'uro';
    const BCY = 'bcy';

    /**
     * @param string $type
     * @return Currency
     * @throws \Exception
     */
    public static function currency($type)
    {
        // TODO: Code Review. Duplicate code: AccountType::currency
        switch ($type) {
            case WalletCoin::BTC:
                return new Currency('BTC');
            case WalletCoin::BTC_TESTNET:
                return new Currency('BTC');
            case WalletCoin::BCY:
                return new Currency('BTC');
            case WalletCoin::LTC:
                return new Currency('LTC'); // TODO: add to mathiasverraes/money/lib/Money/currencies.php
            case WalletCoin::DOGE:
                return new Currency('DOGE'); // TODO: add to mathiasverraes/money/lib/Money/currencies.php
            case WalletCoin::URO:
                return new Currency('URO'); // TODO: add to mathiasverraes/money/lib/Money/currencies.php
            default:
                throw new \Exception(sprintf("Unsupported account type %s", $type));
        }
    }
}