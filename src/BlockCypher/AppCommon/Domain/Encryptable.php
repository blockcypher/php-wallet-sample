<?php

namespace BlockCypher\AppCommon\Domain;

use BlockCypher\AppCommon\App\Service\Encryptor;

/**
 * Interface Encryptable
 * @package BlockCypher\AppCommon\App\Service
 */
interface Encryptable
{
    /**
     * @param Encryptor $encryptor
     * @return mixed
     */
    public function encryptUsing(Encryptor $encryptor);
}