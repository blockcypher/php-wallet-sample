<?php

namespace BlockCypher\AppWallet\Domain\Wallet;

use BlockCypher\AppCommon\Domain\Enum;
use Money\Currency;

/**
 * Class WalletCoinSymbol
 * @package BlockCypher\AppWallet\Domain\Wallet
 */
class WalletCoinSymbol extends Enum
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
        // TODO: Code Review
        switch ($type) {
            case WalletCoinSymbol::BTC:
                return new Currency('BTC');
            case WalletCoinSymbol::BTC_TESTNET:
                return new Currency('BTC');
            case WalletCoinSymbol::BCY:
                return new Currency('BTC');
            case WalletCoinSymbol::LTC:
                return new Currency('LTC'); // TODO: add to mathiasverraes/money/lib/Money/currencies.php
            case WalletCoinSymbol::DOGE:
                return new Currency('DOGE'); // TODO: add to mathiasverraes/money/lib/Money/currencies.php
            case WalletCoinSymbol::URO:
                return new Currency('URO'); // TODO: add to mathiasverraes/money/lib/Money/currencies.php
            default:
                throw new \Exception(sprintf("Unsupported type %s", $type));
        }
    }
}