<?php

namespace BlockCypher\AppWallet\App\Service;

use BlockCypher\AppWallet\Domain\Account\AccountId;
use BlockCypher\AppWallet\Domain\Address\Address;

interface AddressService
{
    /**
     * @return Address[]
     */
    public function listAccountAddresses(AccountId $accountId);
}