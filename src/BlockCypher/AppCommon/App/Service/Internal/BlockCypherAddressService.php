<?php

namespace BlockCypher\AppCommon\App\Service\Internal;

use BlockCypher\Api\Address;
use BlockCypher\Api\AddressBalance;
use BlockCypher\Client\AddressClient;

/**
 * Class BlockCypherAddressService
 * @package BlockCypher\AppCommon\App\Service\Internal
 */
class BlockCypherAddressService
{
    /**
     * @var BlockCypherApiContextFactory
     */
    private $apiContextFactory;

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
        $addressClient = new AddressClient($apiContext);

        $address = $addressClient->get($address);

        return $address;
    }

    /**
     * @param string[] $addressList
     * @param $coinSymbol
     * @param $token
     * @return AddressBalance[]
     */
    public function getMultipleAddressBalance($addressList, $coinSymbol, $token)
    {
        $apiContext = $this->apiContextFactory->getApiContext($coinSymbol, $token);
        $addressClient = new AddressClient($apiContext);

        $addressBalances = $addressClient->getMultipleBalances($addressList);

        return $addressBalances;
    }
}