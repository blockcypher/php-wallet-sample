<?php

namespace BlockCypher\AppCommon\App\Service\Internal;

use BlockCypher\Client\FaucetClient;

/**
 * Class BlockCypherFaucetService
 * @package BlockCypher\AppCommon\App\Service\Internal
 */
class BlockCypherFaucetService
{
    /**
     * @var BlockCypherApiContextFactory
     */
    private $apiContextFactory;

    /**
     * @param BlockCypherApiContextFactory $apiContextFactory
     */
    public function __construct(BlockCypherApiContextFactory $apiContextFactory)
    {
        $this->apiContextFactory = $apiContextFactory;
    }

    /**
     * @param string $address
     * @param int $amount
     * @param string $coinSymbol
     * @param string $token
     * @return string
     */
    public function fundAddress($address, $amount, $coinSymbol, $token)
    {
        $apiContext = $this->apiContextFactory->getApiContext($coinSymbol, $token);
        $faucetClient = new FaucetClient($apiContext);

        $response = $faucetClient->fundAddress($address, $amount);

        return $response->getTxRef();
    }
}