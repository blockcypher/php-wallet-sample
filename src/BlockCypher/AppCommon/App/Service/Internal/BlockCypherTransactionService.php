<?php

namespace BlockCypher\AppCommon\App\Service\Internal;

use BlockCypher\Api\TX;

class BlockCypherTransactionService
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
     * @return TX
     */
    public function getTransaction($hash, $params, $coinSymbol, $token)
    {
        $apiContext = $this->apiContextFactory->getApiContext($coinSymbol, $token);

        $transaction = TX::get($hash, $params, $apiContext);

        return $transaction;
    }

    /**
     * @param string[] $hashArray
     * @param array $params
     * @param $coinSymbol
     * @param $token
     * @return TX[]
     */
    public function getTransactions($hashArray, $params, $coinSymbol, $token)
    {
        $apiContext = $this->apiContextFactory->getApiContext($coinSymbol, $token);

        $transaction = TX::getMultiple($hashArray, $params, $apiContext);

        return $transaction;
    }
}