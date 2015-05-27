<?php

namespace BlockCypher\AppWallet\App\Command;

use SimpleBus\Message\Name\NamedMessage;

class CreateAddressCommand implements NamedMessage
{
    /**
     * @var string
     */
    private $accountId;

    /**
     * @var string
     */
    private $tag;

    /**
     * @var string
     */
    private $callbackUrl;

    /**
     * @param $accountId
     * @param string $tag
     * @param string $callbackUrl
     */
    public function __construct($accountId, $tag = '', $callbackUrl = '')
    {
        $this->accountId = $accountId;
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
    public function getAccountId()
    {
        return $this->accountId;
    }

    /**
     * @param string $accountId
     * @return $this
     */
    public function setAccountId($accountId)
    {
        $this->accountId = $accountId;
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