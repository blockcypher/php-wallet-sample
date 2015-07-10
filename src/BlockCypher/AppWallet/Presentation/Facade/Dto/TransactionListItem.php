<?php

namespace BlockCypher\AppWallet\Presentation\Facade\Dto;

use BlockCypher\Api\TXRef as BlockCypherTXRef;
use BlockCypher\AppWallet\Domain\Transaction\Transaction;
use BlockCypher\AppWallet\Presentation\Utils\DateTimeFactory;
use DateTime;

/**
 * Class TransactionListItem
 * @package BlockCypher\AppWallet\Presentation\Facade\Dto
 */
class TransactionListItem
{
    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $txHash;

    /**
     * @var int
     */
    private $txInputN;

    /**
     * @var int
     */
    private $value;

    /**
     * @var  int
     */
    private $confirmations;

    /**
     * @var string
     */
    private $received;

    /**
     * @var string
     */
    private $confirmed;

    /**
     * @var int
     */
    private $blockHeight;

    /**
     * @var int
     */
    private $balance;

    /**
     * @var string
     */
    private $explorerUrl;

    /**
     * @var int
     */
    private $inputsTotal;

    /**
     * @var string
     */
    private $apiUrl;

    /**
     * @var int
     */
    private $outputsTotal;

    function __construct()
    {
        $this->inputsTotal = 0;
        $this->outputsTotal = 0;
    }

    /**
     * @param BlockCypherTXRef $blockCypherTXRef
     * @return TransactionListItem
     * @throws \Exception
     */
    public static function from(BlockCypherTXRef $blockCypherTXRef)
    {
        $transactionListItem = new self();

        // DEBUG
        //var_dump($blockCypherTXRef);
        //die();

        $transactionListItem->setTxHash($blockCypherTXRef->getTxHash());
        $transactionListItem->setConfirmations($blockCypherTXRef->getConfirmations());
        if ($blockCypherTXRef->getReceived() !== null) {
            $transactionListItem->setReceived(DateTimeFactory::fromISO8601($blockCypherTXRef->getReceived()));
        }
        if ($blockCypherTXRef->getConfirmed() !== null) {
            $transactionListItem->setConfirmed(DateTimeFactory::fromISO8601($blockCypherTXRef->getConfirmed()));
        }
        $transactionListItem->setBlockHeight($blockCypherTXRef->getBlockHeight());

        return $transactionListItem;
    }

    /**
     * @param int $amount
     */
    public function incrementInputsTotal($amount)
    {
        $this->inputsTotal += $amount;
    }

    /**
     * @param int $amount
     */
    public function incrementOutputsTotal($amount)
    {
        $this->outputsTotal += $amount;
    }

    /**
     * @return int
     */
    public function getFinalTotal()
    {
        return $this->outputsTotal - $this->inputsTotal;
    }


    /**
     * @param Transaction|null $transaction
     * @param BlockCypherTXRef $blockCypherTXRef
     * @param string $apiUrl
     * @param string $explorerUrl
     * @return $this
     */
//    public static function from(
//        $transaction,
//        BlockCypherTXRef $blockCypherTXRef,
//        $apiUrl,
//        $explorerUrl
//    )
//    {
//        $transactionListItemDto = new self();
//
//        // From local app transaction
//        if ($transaction !== null) {
//            $transactionListItemDto->setDescription($transaction->getDescription());
//        } else {
//            $transactionListItemDto->setDescription($blockCypherTXRef->getTxHash());
//        }
//
//        // From BlockCypher TXRef
//        $transactionListItemDto->setTxHash($blockCypherTXRef->getTxHash());
//        $transactionListItemDto->setTxInputN($blockCypherTXRef->getTxInputN());
//        $transactionListItemDto->setValue($blockCypherTXRef->getValue());
//        $transactionListItemDto->setConfirmations($blockCypherTXRef->getConfirmations());
//        $transactionListItemDto->setReceived(DateTimeFactory::fromISO8601($blockCypherTXRef->getReceived()));
//        $transactionListItemDto->setConfirmed(DateTimeFactory::fromISO8601($blockCypherTXRef->getConfirmed()));
//        $transactionListItemDto->setBlockHeight($blockCypherTXRef->getBlockHeight());
//
//        $transactionListItemDto->setApiUrl($apiUrl);
//        $transactionListItemDto->setExplorerUrl($explorerUrl);
//
//        return $transactionListItemDto;
//    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return string
     */
    public function getTxHash()
    {
        return $this->txHash;
    }

    /**
     * @param string $txHash
     * @return $this
     */
    public function setTxHash($txHash)
    {
        $this->txHash = $txHash;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTxInputN()
    {
        return $this->txInputN;
    }

    /**
     * @param mixed $txInputN
     * @return $this
     */
    public function setTxInputN($txInputN)
    {
        $this->txInputN = $txInputN;
        return $this;
    }

    /**
     * @return int
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param int $value
     * @return $this
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @return int
     */
    public function getConfirmations()
    {
        return $this->confirmations;
    }

    /**
     * @param int $confirmations
     * @return $this
     */
    public function setConfirmations($confirmations)
    {
        $this->confirmations = $confirmations;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getReceived()
    {
        return $this->received;
    }

    /**
     * @param DateTime $received
     * @return $this
     */
    public function setReceived(DateTime $received)
    {
        $this->received = clone $received;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getConfirmed()
    {
        return $this->confirmed;
    }

    /**
     * @param DateTime $confirmed
     * @return $this
     */
    public function setConfirmed(DateTime $confirmed)
    {
        $this->confirmed = clone $confirmed;
        return $this;
    }

    /**
     * @return int
     */
    public function getBlockHeight()
    {
        return $this->blockHeight;
    }

    /**
     * @param int $blockHeight
     * @return $this
     */
    public function setBlockHeight($blockHeight)
    {
        $this->blockHeight = $blockHeight;
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
    public function getInputsTotal()
    {
        return $this->inputsTotal;
    }

    /**
     * @param int $inputsTotal
     * @return $this
     */
    public function setInputsTotal($inputsTotal)
    {
        $this->inputsTotal = $inputsTotal;
        return $this;
    }

    /**
     * @return int
     */
    public function getOutputsTotal()
    {
        return $this->outputsTotal;
    }

    /**
     * @param int $outputsTotal
     * @return $this
     */
    public function setOutputsTotal($outputsTotal)
    {
        $this->outputsTotal = $outputsTotal;
        return $this;
    }

    /**
     * @return string
     */
    public function getApiUrl()
    {
        return $this->apiUrl;
    }

    /**
     * @param string $apiUrl
     * @return $this
     */
    public function setApiUrl($apiUrl)
    {
        $this->apiUrl = $apiUrl;
        return $this;
    }

    /**
     * @return string
     */
    public function getExplorerUrl()
    {
        return $this->explorerUrl;
    }

    /**
     * @param string $explorerUrl
     * @return $this
     */
    public function setExplorerUrl($explorerUrl)
    {
        $this->explorerUrl = $explorerUrl;
        return $this;
    }
}