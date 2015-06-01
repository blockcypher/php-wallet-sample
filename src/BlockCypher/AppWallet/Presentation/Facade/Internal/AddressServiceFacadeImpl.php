<?php

namespace BlockCypher\AppWallet\Presentation\Facade\Internal;

use BlockCypher\AppWallet\App\Service\AddressService;
use BlockCypher\AppWallet\Domain\Account\AccountId;
use BlockCypher\AppWallet\Domain\Address\Address;
use BlockCypher\AppWallet\Presentation\Facade\AddressServiceFacade;

class AddressServiceFacadeImpl implements AddressServiceFacade
{
    /**
     * @var AddressService
     */
    private $addressService;

    /**
     * @param AddressService $addressService
     */
    function __construct(AddressService $addressService)
    {
        $this->addressService = $addressService;
    }

    /**
     * @param string $accountId
     * @return array
     */
    public function listAccountAddresses($accountId)
    {
        $addresses = $this->addressService->listAccountAddresses(new AccountId($accountId));
        $addressDTOs = Address::ObjectArrayToArray($addresses);
        return $addressDTOs;
    }
}