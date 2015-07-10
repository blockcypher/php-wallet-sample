<?php

namespace BlockCypher\AppWallet\App\Command;

use SimpleBus\Message\Name\NamedMessage;

/**
 * Class CreateTransactionCommand
 * @package BlockCypher\AppWallet\App\Command
 */
class CreateTransactionCommand implements NamedMessage
{
    /**
     * @var string
     */
    private $walletId;

    /**
     * @var string
     */
    private $payToAddress;

    /**
     * @var string
     */
    private $description;

    /**
     * @var int
     */
    private $amount;

    /**
     * @param string $walletId
     * @param string $payToAddress
     * @param string $description
     * @param int $amount
     */
    public function __construct(
        $walletId,
        $payToAddress = '',
        $description = '',
        $amount = 0
    )
    {
        $this->walletId = $walletId;
        $this->payToAddress = $payToAddress;
        $this->description = $description;
        $this->amount = (int)$amount;
    }

    /**
     * The name of this particular type of message.
     *
     * @return string
     */
    public static function messageName()
    {
        return 'bc_app_wallet_transaction_create_transaction';
    }

    /**
     * @return string
     */
    public function getWalletId()
    {
        return $this->walletId;
    }

    /**
     * @param string $walletId
     * @return $this
     */
    public function setWalletId($walletId)
    {
        $this->walletId = $walletId;
        return $this;
    }

    /**
     * @return string
     */
    public function getPayToAddress()
    {
        return $this->payToAddress;
    }

    /**
     * @param string $payToAddress
     * @return $this
     */
    public function setPayToAddress($payToAddress)
    {
        $this->payToAddress = $payToAddress;
        return $this;
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
     * @return int
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param int $amount
     * @return $this
     */
    public function setAmount($amount)
    {
        $this->amount = (int)$amount;
        return $this;
    }
}