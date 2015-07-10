<?php

namespace BlockCypher\AppWallet\Presentation\Facade\Dto;

use BlockCypher\AppWallet\App\Service\ApiRouter;
use BlockCypher\AppWallet\App\Service\ExplorerRouter;
use BlockCypher\AppWallet\Domain\Transaction\Transaction;
use BlockCypher\AppWallet\Domain\Wallet\Wallet;

/**
 * Class TransactionListItemDtoArray
 * @package BlockCypher\AppWallet\Presentation\Facade\Dto
 */
class TransactionListItemDtoArray
{
    /**
     * @param Wallet $wallet
     * @param Transaction[] $transactions
     * @param TransactionListItem[] $transactionListItems
     * @param ApiRouter $apiRouter
     * @param ExplorerRouter $explorerRouter
     * @return TransactionListItemDto[]
     */
    public static function from(
        Wallet $wallet,
        $transactions,
        $transactionListItems,
        ApiRouter $apiRouter,
        ExplorerRouter $explorerRouter
    )
    {
        // DEBUG
        //var_dump($transactionListItems);
        //die();

        $transactionListItemDtos = array();
        foreach ($transactionListItems as $transactionListItem) {

            $txHash = $transactionListItem->getTxHash();

            if (isset($transactions[$txHash])) {
                $transaction = $transactions[$txHash];
            } else {
                $transaction = null;
            }

            $transactionListItemDto = TransactionListItemDto::from(
                $transaction,
                $transactionListItem,
                $apiRouter->transaction($txHash, $wallet->getCoinSymbol(), $wallet->getToken()),
                $explorerRouter->transaction($txHash, $wallet->getCoinSymbol(), $wallet->getToken())
            );

            $transactionListItemDtos[] = $transactionListItemDto;
        }

        // DEBUG
        //var_dump($transactionListItemDtos);
        //die();

        return $transactionListItemDtos;
    }
}