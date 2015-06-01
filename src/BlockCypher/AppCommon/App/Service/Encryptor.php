<?php

namespace BlockCypher\AppCommon\App\Service;

/**
 * Interface Encryptor
 * @package BlockCypher\AppCommon\App\Service
 */
interface Encryptor
{
    /**
     * @param $dataToEncrypt
     * @return string
     */
    public function encrypt($dataToEncrypt);
}