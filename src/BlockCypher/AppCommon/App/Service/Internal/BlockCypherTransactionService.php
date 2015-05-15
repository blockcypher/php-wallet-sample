<?php

namespace BlockCypher\AppCommon\App\Service\Internal;

use BlockCypher\Api\Transaction;
use BlockCypher\AppCommon\App\Service\TransactionService;

class BlockCypherTransactionService implements TransactionService
{
    /**
     * @param BlockCypherApiContextFactory $apiContextFactory
     */
    public function __construct(BlockCypherApiContextFactory $apiContextFactory)
    {
        $this->apiContextFactory = $apiContextFactory;
    }

    /**
     * @param string $hash
     * @param array $params
     * @param $coinSymbol
     * @param $token
     * @return Transaction
     */
    public function getTransaction($hash, $params, $coinSymbol, $token)
    {
        $apiContext = $this->apiContextFactory->getApiContext($token);

        $transaction = Transaction::get($hash, $params, $apiContext);

        return $transaction;
    }

    /**
     * @param string[] $hashArray
     * @param array $params
     * @param $coinSymbol
     * @param $token
     * @return Transaction[]
     */
    public function getTransactions($hashArray, $params, $coinSymbol, $token)
    {
        $apiContext = $this->apiContextFactory->getApiContext($token);

        $transaction = Transaction::getMultiple($hashArray, $params, $apiContext);

        return $transaction;
    }
}