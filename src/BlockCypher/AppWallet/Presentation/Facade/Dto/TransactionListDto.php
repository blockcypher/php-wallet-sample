<?php

namespace BlockCypher\AppWallet\Presentation\Facade\Dto;

use BlockCypher\Api\Address as BlockCypherAddress;
use BlockCypher\AppWallet\App\Service\ApiRouter;
use BlockCypher\AppWallet\App\Service\ExplorerRouter;
use BlockCypher\AppWallet\Domain\Transaction\Transaction;
use BlockCypher\AppWallet\Domain\Wallet\Wallet;

/**
 * Class TransactionListDto
 * @package BlockCypher\AppWallet\Presentation\Facade\Dto
 */
class TransactionListDto
{
    /**
     * @var int
     */
    private $totalSent;

    /**
     * @var int
     */
    private $totalReceived;

    /**
     * @var int
     */
    private $unconfirmedBalance;

    /**
     * @var int
     */
    private $balance;

    /**
     * @var int
     */
    private $finalBalance;

    /**
     * @var int
     */
    private $nTx;

    /**
     * @var int
     */
    private $unconfirmedNTx;

    /**
     * @var int
     */
    private $finalNTx;

    /**
     * TransactionListItemDto[]
     */
    private $transactionListItemDtos;

    /**
     * @param Wallet $wallet
     * @param Transaction[]|null $transactions
     * @param BlockCypherAddress $blockCypherAddress
     * @param ApiRouter $apiRouter
     * @param ExplorerRouter $explorerRouter
     * @return TransactionListDto
     */
    public static function from(
        Wallet $wallet,
        $transactions,
        BlockCypherAddress $blockCypherAddress,
        ApiRouter $apiRouter,
        ExplorerRouter $explorerRouter
    )
    {
        $transactionListDto = new self();

        // From BlockCypher Address
        $transactionListDto->setTotalSent($blockCypherAddress->getTotalSent());
        $transactionListDto->setTotalReceived($blockCypherAddress->getTotalReceived());
        $transactionListDto->setUnconfirmedBalance($blockCypherAddress->getUnconfirmedBalance());
        $transactionListDto->setBalance($blockCypherAddress->getBalance());
        $transactionListDto->setFinalBalance($blockCypherAddress->getFinalBalance());
        $transactionListDto->setNTx($blockCypherAddress->getNTx());
        $transactionListDto->setUnconfirmedNTx($blockCypherAddress->getUnconfirmedNTx());
        $transactionListDto->setFinalNTx($blockCypherAddress->getFinalNTx());

        $blockCypherTXRefs = $blockCypherAddress->getAllTxrefs(); // Confirmed and unconfirmed

        $transactionListItems = TransactionListItemArray::from($blockCypherTXRefs);

        $transactionListItemDtos = TransactionListItemDtoArray::from(
            $wallet,
            $transactions,
            $transactionListItems,
            $apiRouter,
            $explorerRouter
        );

        $transactionListDto->setTransactionListItemDtos($transactionListItemDtos);

        return $transactionListDto;
    }

    /**
     * @return TransactionListItemDto[]
     */
    public function getTransactionListItemDtos()
    {
        return $this->transactionListItemDtos;
    }

    /**
     * @param TransactionListItemDto[] $transactionListItemDtos
     * @return $this
     */
    public function setTransactionListItemDtos($transactionListItemDtos)
    {
        $this->transactionListItemDtos = $transactionListItemDtos;
        return $this;
    }

    /**
     * @return int
     */
    public function getTotalSent()
    {
        return $this->totalSent;
    }

    /**
     * @param int $totalSent
     * @return $this
     */
    public function setTotalSent($totalSent)
    {
        $this->totalSent = $totalSent;
        return $this;
    }

    /**
     * @return int
     */
    public function getTotalReceived()
    {
        return $this->totalReceived;
    }

    /**
     * @param int $totalReceived
     * @return $this
     */
    public function setTotalReceived($totalReceived)
    {
        $this->totalReceived = $totalReceived;
        return $this;
    }

    /**
     * @return int
     */
    public function getUnconfirmedBalance()
    {
        return $this->unconfirmedBalance;
    }

    /**
     * @param int $unconfirmedBalance
     * @return $this
     */
    public function setUnconfirmedBalance($unconfirmedBalance)
    {
        $this->unconfirmedBalance = $unconfirmedBalance;
        return $this;
    }

    /**
     * @return int
     */
    public function getBalance()
    {
        return $this->balance;
    }

    /**
     * @param int $balance
     * @return $this
     */
    public function setBalance($balance)
    {
        $this->balance = $balance;
        return $this;
    }

    /**
     * @return int
     */
    public function getFinalBalance()
    {
        return $this->finalBalance;
    }

    /**
     * @param int $finalBalance
     * @return $this
     */
    public function setFinalBalance($finalBalance)
    {
        $this->finalBalance = $finalBalance;
        return $this;
    }

    /**
     * @return int
     */
    public function getNTx()
    {
        return $this->nTx;
    }

    /**
     * @param int $nTx
     * @return $this
     */
    public function setNTx($nTx)
    {
        $this->nTx = $nTx;
        return $this;
    }

    /**
     * @return int
     */
    public function getUnconfirmedNTx()
    {
        return $this->unconfirmedNTx;
    }

    /**
     * @param int $unconfirmedNTx
     * @return $this
     */
    public function setUnconfirmedNTx($unconfirmedNTx)
    {
        $this->unconfirmedNTx = $unconfirmedNTx;
        return $this;
    }

    /**
     * @return int
     */
    public function getFinalNTx()
    {
        return $this->finalNTx;
    }

    /**
     * @param int $finalNTx
     * @return $this
     */
    public function setFinalNTx($finalNTx)
    {
        $this->finalNTx = $finalNTx;
        return $this;
    }
}