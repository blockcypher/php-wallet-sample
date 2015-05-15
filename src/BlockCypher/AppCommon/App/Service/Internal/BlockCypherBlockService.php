<?php

namespace BlockCypher\AppCommon\App\Service\Internal;

use BlockCypher\Api\Block;
use BlockCypher\AppCommon\App\Service\BlockService;

class BlockCypherBlockService implements BlockService
{
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
        $apiContext = $this->apiContextFactory->getApiContext($token);

        $block = Block::get($hashOrHeight, array(), $apiContext);

        return $block;
    }
}