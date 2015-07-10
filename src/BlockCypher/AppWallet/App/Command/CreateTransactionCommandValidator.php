<?php

namespace BlockCypher\AppWallet\App\Command;

use Assert\Assertion;
use BlockCypher\AppCommon\App\Command\CommandValidator;

/**
 * Class CreateTransactionCommandValidator
 * @package BlockCypher\AppWallet\App\Command
 */
class CreateTransactionCommandValidator implements CommandValidator
{
    /**
     * @param CreateTransactionCommand $createTransactionCommand
     * @return void
     */
    public function validate($createTransactionCommand)
    {
        Assertion::integer($createTransactionCommand->getAmount());
    }
}