<?php

namespace BlockCypher\AppCommon\App\Service;

use BlockCypher\Api\Transaction;

interface TransactionService
{
    /**
     * @param string $hash
     * @param array $params
     * @param $coinSymbol
     * @param $token
     * @return Transaction
     */
    public function getTransaction($hash, $params, $coinSymbol, $token);

    /**
     * @param string $hashArray
     * @param array $params
     * @param $coinSymbol
     * @param $token
     * @return Transaction[]
     */
    public function getTransactions($hashArray, $params, $coinSymbol, $token);
}