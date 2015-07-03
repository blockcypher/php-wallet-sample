<?php

namespace BlockCypher\AppCommon\App\Service\Internal;

use BlockCypher\Api\Address;

class BlockCypherAddressService
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
        $apiContext = $this->apiContextFactory->getApiContext($coinSymbol, $token);

        $address = Address::get($address, array(), $apiContext);

        return $address;
    }
}