<?php

namespace BlockCypher\AppWallet\Presentation\Facade\Dto;

use BlockCypher\Api\Address as BlockCypherAddress;
use BlockCypher\AppWallet\Domain\Wallet\Wallet;

/**
 * Class WalletDto
 * @package BlockCypher\AppWallet\Presentation\Facade\Dto
 */
class WalletDto
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $userId;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $coinSymbol;

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
     * @param Wallet $wallet
     * @param BlockCypherAddress $blockCypherAddress
     * @return WalletDto
     */
    public static function from(Wallet $wallet, BlockCypherAddress $blockCypherAddress)
    {
        $walletDto = new WalletDto();

        // From Wallet
        $walletDto->setUserId($wallet->getUserId()->getValue());
        $walletDto->setName($wallet->getName());
        $walletDto->setCoinSymbol($wallet->getCoinSymbol());

        // From BlockCypherAddress
        $walletDto->setId($blockCypherAddress->getWallet()->getName());
        $walletDto->setTotalSent($blockCypherAddress->getTotalSent());
        $walletDto->setTotalReceived($blockCypherAddress->getTotalReceived());
        $walletDto->setUnconfirmedBalance($blockCypherAddress->getUnconfirmedBalance());
        $walletDto->setBalance($blockCypherAddress->getBalance());
        $walletDto->setFinalBalance($blockCypherAddress->getFinalBalance());
        $walletDto->setNTx($blockCypherAddress->getNTx());
        $walletDto->setUnconfirmedNTx($blockCypherAddress->getUnconfirmedNTx());
        $walletDto->setFinalNTx($blockCypherAddress->getFinalNTx());

        return $walletDto;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param string $userId
     * @return $this
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getCoinSymbol()
    {
        return $this->coinSymbol;
    }

    /**
     * @param string $coinSymbol
     * @return $this
     */
    public function setCoinSymbol($coinSymbol)
    {
        $this->coinSymbol = $coinSymbol;
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