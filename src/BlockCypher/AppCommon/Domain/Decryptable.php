<?php

namespace BlockCypher\AppCommon\Domain;

use BlockCypher\AppCommon\App\Service\Decryptor;

/**
 * Interface Decryptable
 * @package BlockCypher\AppCommon\Domain
 */
interface Decryptable
{
    /**
     * @param Decryptor $decryptor
     * @return mixed
     */
    public function decryptUsing(Decryptor $decryptor);
}