<?php

namespace BlockCypher\AppCommon\App\Service;

use BlockCypher\Api\Address;

interface AddressService
{
    /**
     * @param string $address
     * @param $coinSymbol
     * @param $token
     * @return Address
     */
    public function getAddress($address, $coinSymbol, $token);
}