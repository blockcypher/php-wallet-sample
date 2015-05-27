<?php

namespace BlockCypher\AppWallet\Presentation\Facade;

interface AddressServiceFacade
{
    /**
     * @param string $accountId
     * @return array
     */
    public function listAccountAddresses($accountId);
}