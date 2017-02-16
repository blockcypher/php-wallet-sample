<?php

namespace BlockCypher\AppWallet\App\Command;

use BlockCypher\AppCommon\App\Command\CommandValidator;

/**
 * Class FundAddressCommandValidator
 * @package BlockCypher\AppWallet\App\Command
 */
class FundAddressCommandValidator implements CommandValidator
{
    /**
     * @param FundAddressCommand $fundAddressCommand
     * @return void
     */
    public function validate($fundAddressCommand)
    {
        // TODO: check that coinSymbol is bcy or btc-testnet
    }
}