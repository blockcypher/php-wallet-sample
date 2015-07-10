<?php

namespace BlockCypher\AppWallet\Presentation\Facade\Dto;

use BlockCypher\Api\AddressBalance as BlockCypherAddressBalance;
use BlockCypher\AppWallet\Domain\Address\Address;

/**
 * Class AddressListItemDto
 * @package BlockCypher\AppWallet\Presentation\Facade\Dto
 */
class AddressListItemDto
{
    /**
     * @var string
     */
    private $tag;

    /**
     * @var int
     */
    private $finalBalance;

    /**
     * @var int
     */
    private $nTx;

    /**
     * @var string
     */
    private $address;

    /**
     * Entity creation time.
     *
     * @var \DateTime
     */
    private $creationTime;

    /**
     * @var string
     */
    private $apiUrl;

    /**
     * @var string
     */
    private $explorerUrl;

    /**
     * @param Address $address
     * @param BlockCypherAddressBalance $blockCypherAddressBalance
     * @param string $apiUrl
     * @param string $explorerUrl
     * @return $this
     */
    public static function from(
        Address $address,
        BlockCypherAddressBalance $blockCypherAddressBalance,
        $apiUrl,
        $explorerUrl
    )
    {
        $addressListItemDto = new self();
        $addressListItemDto->setAddress($address->getAddress());
        $addressListItemDto->setTag($address->getTag());
        $addressListItemDto->setCreationTime($address->getCreationTime());
        $addressListItemDto->setFinalBalance($blockCypherAddressBalance->getFinalBalance());
        $addressListItemDto->setNTx($blockCypherAddressBalance->getNTx());
        $addressListItemDto->setApiUrl($apiUrl);
        $addressListItemDto->setExplorerUrl($explorerUrl);

        return $addressListItemDto;
    }

    /**
     * @return string
     */
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * @param string $tag
     * @return $this
     */
    public function setTag($tag)
    {
        $this->tag = $tag;
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
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param string $address
     * @return $this
     */
    public function setAddress($address)
    {
        $this->address = $address;
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