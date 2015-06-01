<?php

namespace BlockCypher\AppCommon\Domain;

use BlockCypher\AppCommon\App\Service\Encryptor;
use BlockCypher\AppWallet\Domain\Account\EncryptedAccount;

/**
 * Interface Encryptable
 * @package BlockCypher\AppCommon\App\Service
 */
interface Encryptable
{
    /**
     * @param Encryptor $encryptor
     * @return EncryptedAccount
     */
    public function encryptUsing(Encryptor $encryptor);
}