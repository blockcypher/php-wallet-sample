<?php

namespace BlockCypher\AppCommon\App\Service;

/**
 * Interface Decryptor
 * @package BlockCypher\AppCommon\App\Service
 */
interface Decryptor
{
    /**
     * @param $dataToDecrypt
     * @return mixed
     */
    public function decrypt($dataToDecrypt);
}