<?php

namespace BlockCypher\AppCommon\App\Service;

use BlockCypher\Api\Block;

interface BlockService
{
    /**
     * @param $hashOrHeight
     * @param $coinSymbol
     * @param $token
     * @return Block
     */
    public function getBlock($hashOrHeight, $coinSymbol, $token);
}