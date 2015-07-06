<?php

namespace BlockCypher\AppWallet\Presentation\Facade\Dto;

/**
 * Class TransactionListItemDto
 * @package BlockCypher\AppWallet\Presentation\Facade\Dto
 */
class TransactionListItemDto
{
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
     * @return string
     */
    public function getReceived()
    {
        return $this->received;
    }

    /**
     * @param string $received
     * @return $this
     */
    public function setReceived($received)
    {
        $this->received = $received;
        return $this;
    }

    /**
     * @return string
     */
    public function getConfirmed()
    {
        return $this->confirmed;
    }

    /**
     * @param string $confirmed
     * @return $this
     */
    public function setConfirmed($confirmed)
    {
        $this->confirmed = $confirmed;
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
}