<?php

namespace BlockCypher\AppWallet\Presentation\Facade\Dto;

use BlockCypher\AppWallet\Domain\Transaction\Transaction;
use DateTime;

/**
 * Class TransactionListItemDto
 * @package BlockCypher\AppWallet\Presentation\Facade\Dto
 */
class TransactionListItemDto
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
     * @var int
     */
    private $total;

    /**
     * @var string
     */
    private $apiUrl;

    /**
     * @var string
     */
    private $explorerUrl;

    /**
     * @param Transaction|null $transaction
     * @param TransactionListItem $transactionListItem
     * @param string $apiUrl
     * @param string $explorerUrl
     * @return $this
     */
    public static function from(
        $transaction,
        TransactionListItem $transactionListItem,
        $apiUrl,
        $explorerUrl
    )
    {
        $transactionListItemDto = new self();

        // From local app transaction
        if ($transaction !== null) {
            $transactionListItemDto->setDescription($transaction->getDescription());
        } else {
            $transactionListItemDto->setDescription($transactionListItem->getTxHash());
        }

        // From BlockCypher TXRef
        $transactionListItemDto->setTxHash($transactionListItem->getTxHash());

        //$transactionListItemDto->setTxInputN($transactionListItem->getTxInputN());
        //$transactionListItemDto->setValue($transactionListItem->getValue());

        $transactionListItemDto->setConfirmations($transactionListItem->getConfirmations());
        if ($transactionListItem->getReceived() !== null) {
            $transactionListItemDto->setReceived($transactionListItem->getReceived());
        }
        if ($transactionListItem->getConfirmed() !== null) {
            $transactionListItemDto->setConfirmed($transactionListItem->getConfirmed());
        }
        $transactionListItemDto->setBlockHeight($transactionListItem->getBlockHeight());

        $transactionListItemDto->setTotal($transactionListItem->getFinalTotal());

        $transactionListItemDto->setApiUrl($apiUrl);
        $transactionListItemDto->setExplorerUrl($explorerUrl);

        return $transactionListItemDto;
    }

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
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * @param int $total
     * @return $this
     */
    public function setTotal($total)
    {
        $this->total = $total;
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