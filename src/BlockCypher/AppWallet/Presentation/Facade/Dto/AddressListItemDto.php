<?php

namespace BlockCypher\AppWallet\Presentation\Facade\Dto;

/**
 * Class AddressListItemDto
 * @package BlockCypher\AppWallet\Presentation\Facade\Dto
 */
class AddressListItemDto
{
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
    private $tag;

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
}