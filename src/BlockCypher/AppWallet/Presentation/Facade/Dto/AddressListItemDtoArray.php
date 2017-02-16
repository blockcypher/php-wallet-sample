<?php

namespace BlockCypher\AppWallet\Presentation\Facade\Dto;

use BlockCypher\Api\AddressBalance as BlockCypherAddressBalance;
use BlockCypher\AppWallet\App\Service\ApiRouter;
use BlockCypher\AppWallet\App\Service\ExplorerRouter;
use BlockCypher\AppWallet\Domain\Address\Address;
use BlockCypher\AppWallet\Domain\Wallet\Wallet;

/**
 * Class AddressListItemDtoArray
 * @package BlockCypher\AppWallet\Presentation\Facade\Dto
 */
class AddressListItemDtoArray
{
    /**
     * @param Wallet $wallet
     * @param Address[] $addresses
     * @param BlockCypherAddressBalance[] $blockCypherAddressBalances
     * @param ApiRouter $apiRouter
     * @param ExplorerRouter $explorerRouter
     * @return AddressListItemDto[]
     */
    public static function from(
        $wallet,
        $addresses,
        $blockCypherAddressBalances,
        $apiRouter,
        $explorerRouter
    )
    {
        $addressListItemDtos = array();
        foreach ($addresses as $address) {

            $addressListItemDto = AddressListItemDto::from(
                $address,
                $blockCypherAddressBalances[$address->getAddress()],
                $apiRouter->address($address->getAddress(), $wallet->getCoinSymbol(), $wallet->getToken()),
                $explorerRouter->address($address->getAddress(), $wallet->getCoinSymbol())
            );

            $addressListItemDtos[] = $addressListItemDto;
        }
        return $addressListItemDtos;
    }
}