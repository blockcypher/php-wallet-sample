<?php

namespace BlockCypher\AppExplorer\Presentation\Facade;

interface AddressServiceFacade
{
    /**
     * @param string $address
     * @param $coinSymbol
     * @param $token
     * @return array
     */
    public function getAddress($address, $coinSymbol, $token);
}