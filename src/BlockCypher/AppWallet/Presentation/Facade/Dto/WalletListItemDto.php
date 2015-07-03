<?php

namespace BlockCypher\AppWallet\Presentation\Facade\Dto;

/**
 * Class WalletListItemDto
 * @package BlockCypher\AppWallet\Presentation\Facade\Dto
 */
class WalletListItemDto
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string WalletCoinSymbolSymbol enum
     */
    private $coinSymbol;

    /**
     * Entity creation time.
     *
     * @var \DateTime
     */
    private $creationTime;

    /**
     * @var string
     */
    private $name;

    /**
     * @var float
     */
    private $balance;

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
     * @return \DateTime
     */
    public function getCreationTime()
    {
        return $this->creationTime;
    }

    /**
     * @param \DateTime $creationTime
     * @return $this
     */
    public function setCreationTime($creationTime)
    {
        $this->creationTime = $creationTime;
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
     * @return float
     */
    public function getBalance()
    {
        return $this->balance;
    }

    /**
     * @param float $balance
     * @return $this
     */
    public function setBalance($balance)
    {
        $this->balance = $balance;
        return $this;
    }
}