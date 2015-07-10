<?php

namespace BlockCypher\AppWallet\Presentation\Facade\Dto;

use BlockCypher\Api\TXRef as BlockCypherTXRef;

/**
 * Class TransactionListItemArray
 * @package BlockCypher\AppWallet\Presentation\Facade\Dto
 */
class TransactionListItemArray
{
    /**
     * @param BlockCypherTXRef[] $blockCypherTXRefs
     * @return TransactionListItem[]
     */
    public static function from($blockCypherTXRefs)
    {
        /** @var TransactionListItem[] $transactionListItems */
        $transactionListItems = array();

        foreach ($blockCypherTXRefs as $blockCypherTXRef) {

            $txHash = $blockCypherTXRef->getTxHash();

            // Create new TransactionListItem if it does not exist.
            if (!isset($transactionListItems[$txHash])) {
                $transactionListItem = TransactionListItem::from($blockCypherTXRef);
                $transactionListItems[$txHash] = $transactionListItem;
            } else {
                $transactionListItem = $transactionListItems[$txHash];
            }

            // Increment totals
            if ($blockCypherTXRef->getTxInputN() >= 0) {
                // TXRef is an Input
                $transactionListItem->incrementInputsTotal($blockCypherTXRef->getValue());
            } else {
                // TXRef is an Output
                $transactionListItem->incrementOutputsTotal($blockCypherTXRef->getValue());
            }
        }

        return $transactionListItems;
    }
}