<?php

namespace BlockCypher\AppWallet\Presentation\Facade\Dto;

use BlockCypher\AppWallet\App\Service\ApiRouter;
use BlockCypher\AppWallet\Domain\Wallet\Wallet;
use Money\BigMoney;

/**
 * Class WalletListItemDtoArray
 * @package BlockCypher\AppWallet\Presentation\Facade\Dto
 */
class WalletListItemDtoArray
{
    /**
     * @param Wallet[] $wallets
     * @param BigMoney[] $walletBalances
     * @param ApiRouter $apiRouter
     * @return array
     */
    public static function from($wallets, $walletBalances, $apiRouter)
    {
        $walletListItemDtos = array();
        foreach ($wallets as $wallet) {

            $apiUrl = $apiRouter->wallet($wallet->getId()->getValue(), $wallet->getCoinSymbol(), $wallet->getToken());

            $walletListItemDto = WalletListItemDto::from(
                $wallet,
                $walletBalances[$wallet->getId()->getValue()],
                $apiUrl
            );

            $walletListItemDtos[] = $walletListItemDto;
        }

        return $walletListItemDtos;
    }
}