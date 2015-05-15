<?php

namespace BlockCypher\AppCommon\App\Service\Internal;

use BlockCypher\Api\Address;
use BlockCypher\AppCommon\App\Service\AddressService;

class BlockCypherAddressService implements AddressService
{
    /**
     * @param BlockCypherApiContextFactory $apiContextFactory
     */
    public function __construct(BlockCypherApiContextFactory $apiContextFactory)
    {
        $this->apiContextFactory = $apiContextFactory;
    }

    /**
     * @param string $address
     * @param $coinSymbol
     * @param $token
     * @return Address
     */
    public function getAddress($address, $coinSymbol, $token)
    {
        $apiContext = $this->apiContextFactory->getApiContext($token);

        $address = Address::get($address, array(), $apiContext);

        return $address;
    }
}