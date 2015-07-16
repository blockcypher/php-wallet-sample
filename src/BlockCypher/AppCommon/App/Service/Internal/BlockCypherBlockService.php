<?php

namespace BlockCypher\AppCommon\App\Service\Internal;

use BlockCypher\Api\Block;

/**
 * Class BlockCypherBlockService
 * @package BlockCypher\AppCommon\App\Service\Internal
 */
class BlockCypherBlockService
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
     * @param $hashOrHeight
     * @param $coinSymbol
     * @param $token
     * @return Block
     */
    public function getBlock($hashOrHeight, $coinSymbol, $token)
    {
        $apiContext = $this->apiContextFactory->getApiContext($coinSymbol, $token);

        $block = Block::get($hashOrHeight, array(), $apiContext);

        return $block;
    }
}