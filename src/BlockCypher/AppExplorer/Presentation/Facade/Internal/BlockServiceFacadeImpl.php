<?php

namespace BlockCypher\AppExplorer\Presentation\Facade\Internal;

use BlockCypher\AppCommon\App\Service\BlockService;
use BlockCypher\AppCommon\App\Service\TransactionService;
use BlockCypher\AppExplorer\Presentation\Facade\BlockServiceFacade;

class BlockServiceFacadeImpl implements BlockServiceFacade
{
    /**
     * @var BlockService
     */
    private $blockService;

    /**
     * @var TransactionService
     */
    private $transactionService;

    /**
     * @param BlockService $blockService
     * @param TransactionService $transactionService
     */
    function __construct(
        BlockService $blockService,
        TransactionService $transactionService)
    {
        $this->blockService = $blockService;
        $this->transactionService = $transactionService;
    }

    /**
     * @param $hashOrHeight
     * @param array $params
     * @param $coinSymbol
     * @param $token
     * @return array
     */
    public function getBlockDetails($hashOrHeight, $params, $coinSymbol, $token)
    {
        $blockDetailsDTO = $this->getBlockOverview($hashOrHeight, $coinSymbol, $token);

        $transactions = $this->transactionService->getTransactions($blockDetailsDTO['txids'], $params, $coinSymbol, $token);

        $transactionDTOs = array();
        foreach ($transactions as $transaction) {
            $transactionDTOs[] = $transaction->toArray();
        }

        // Override transaction hash array with transaction details.
        $blockDetailsDTO['txids'] = $transactionDTOs;

        return $blockDetailsDTO;
    }

    /**
     * @param $hashOrHeight
     * @param $coinSymbol
     * @param $token
     * @return array
     */
    public function getBlockOverview($hashOrHeight, $coinSymbol, $token)
    {
        $blockDetails = $this->blockService->getBlock($hashOrHeight, array(), $coinSymbol, $token);

        if (!$blockDetails) return null;

        $blockDetailsDTO = $blockDetails->toArray();

        return $blockDetailsDTO;
    }
}