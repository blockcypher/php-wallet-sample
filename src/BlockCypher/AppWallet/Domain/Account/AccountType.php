<?php

namespace BlockCypher\AppWallet\Domain\Account;

use BlockCypher\AppCommon\Domain\Enum;

/**
 * Class AccountType
 * @package BlockCypher\AppWallet\Domain\Account
 */
class AccountType extends Enum
{
    const BTC = 'btc';
    const EUR = 'eur';
}