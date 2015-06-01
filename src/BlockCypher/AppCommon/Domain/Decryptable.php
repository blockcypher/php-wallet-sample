<?php

namespace BlockCypher\AppCommon\Domain;

use BlockCypher\AppCommon\App\Service\Decryptor;
use BlockCypher\AppWallet\Domain\Account\Account;

/**
 * Interface Decryptable
 * @package BlockCypher\AppCommon\Domain
 */
interface Decryptable
{
    /**
     * @param Decryptor $decryptor
     * @return Account
     */
    public function decryptUsing(Decryptor $decryptor);
}