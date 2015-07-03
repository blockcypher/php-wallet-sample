<?php

namespace BlockCypher\AppExplorer\Presentation\Facade;

use BlockCypher\AppCommon\App\Service\Internal\BlockCypherAddressService;

class AddressServiceFacade
{
    /**
     * @var BlockCypherAddressService
     */
    private $addressService;

    /**
     * @param BlockCypherAddressService $addressService
     */
    function __construct(BlockCypherAddressService $addressService)
    {
        $this->addressService = $addressService;
    }

    /**
     * @param string $address
     * @param $coinSymbol
     * @param $token
     * @return array
     */
    public function getAddress($address, $coinSymbol, $token)
    {
        /*$apiContext = $this->getApiContext($token);

        $addressDetails = Address::get($address, array(), $apiContext);*/

        $addressDetails = $this->addressService->getAddress($address, $coinSymbol, $token);

        if (!$addressDetails) return null;

        $addressDetailsArray = $addressDetails->toArray();

        // TODO: check hasMore property.

        // merge confirmed and unconfirmed transactions in one array
        $allTransactions = array();
        foreach ($addressDetails->getAllTxrefs() as $txref) {
            $tx = $txref->toArray();
            if ($txref->getReceived() === null) {
                $tx['received'] = null;
            }
            $allTransactions[] = $tx;
        }

        $addressDetailsArray['all_transactions'] = $allTransactions;

        return $addressDetailsArray;
    }
}