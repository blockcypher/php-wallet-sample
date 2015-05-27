<?php

namespace BlockCypher\AppWallet\Domain\Wallet;

use BlockCypher\AppCommon\Domain\Enum;

/**
 * Class WalletCoin
 * @package BlockCypher\AppWallet\Domain\Wallet
 */
class WalletCoin extends Enum
{
    const BTC = 'btc';
    const BTC_TESNET = 'btc-testnet';
    const LTC = 'ltc';
    const DOGE = 'doge';
    const URO = 'uro';
    const BCY = 'bcy';
}