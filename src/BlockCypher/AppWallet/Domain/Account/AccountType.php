<?php

namespace BlockCypher\AppWallet\Domain\Account;

use BlockCypher\AppCommon\Domain\Enum;
use Money\Currency;

/**
 * Class AccountType
 * @package BlockCypher\AppWallet\Domain\Account
 */
class AccountType extends Enum
{
    const BTC = 'btc';
    const BTC_TESTNET = 'btc-testnet';
    const BCY = 'bcy';
    const LTC = 'ltc';
    const DOGE = 'doge';
    const URO = 'uro';
    const EUR = 'eur';

    /**
     * @param string $type
     * @return Currency
     * @throws \Exception
     */
    public static function currency($type)
    {
        switch ($type) {
            case AccountType::BTC:
                return new Currency('BTC');
            case AccountType::BTC_TESTNET:
                return new Currency('BTC');
            case AccountType::BCY:
                return new Currency('BTC');
            case AccountType::LTC:
                return new Currency('LTC'); // TODO: add to mathiasverraes/money/lib/Money/currencies.php
            case AccountType::DOGE:
                return new Currency('DOGE'); // TODO: add to mathiasverraes/money/lib/Money/currencies.php
            case AccountType::URO:
                return new Currency('URO'); // TODO: add to mathiasverraes/money/lib/Money/currencies.php
            case AccountType::EUR:
                return new Currency('EUR');
            default:
                throw new \Exception(sprintf("Unsupported account type %s", $type));
        }
    }
}