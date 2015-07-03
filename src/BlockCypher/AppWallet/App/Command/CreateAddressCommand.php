<?php

namespace BlockCypher\AppWallet\App\Command;

use SimpleBus\Message\Name\NamedMessage;

class CreateAddressCommand implements NamedMessage
{
    /**
     * @var string
     */
    private $walletId;

    /**
     * @var string
     */
    private $tag;

    /**
     * @var string
     */
    private $callbackUrl;

    /**
     * @param string $walletId
     * @param string $tag
     * @param string $callbackUrl
     */
    public function __construct($walletId, $tag = '', $callbackUrl = '')
    {
        $this->walletId = $walletId;
        $this->tag = $tag;
        $this->callbackUrl = $callbackUrl;
    }

    /**
     * The name of this particular type of message.
     *
     * @return string
     */
    public static function messageName()
    {
        return 'bc_app_wallet_address_create_address';
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
     * @return string
     */
    public function getCallbackUrl()
    {
        return $this->callbackUrl;
    }

    /**
     * @param string $callbackUrl
     * @return $this
     */
    public function setCallbackUrl($callbackUrl)
    {
        $this->callbackUrl = $callbackUrl;
        return $this;
    }
}