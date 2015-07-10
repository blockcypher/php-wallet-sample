<?php

namespace BlockCypher\AppWallet\App\Command;

use BlockCypher\AppCommon\App\Command\CommandValidator;

/**
 * Class CreateAddressCommandValidator
 * @package BlockCypher\AppWallet\App\Command
 */
class CreateAddressCommandValidator implements CommandValidator
{
    /**
     * @param CreateAddressCommand $createAddressCommand
     * @return void
     */
    public function validate($createAddressCommand)
    {
    }
}