<?php

namespace BlockCypher\AppWallet\App\Command;

use SimpleBus\Message\Name\NamedMessage;

/**
 * Class CreateWalletCommand
 * @package BlockCypher\AppWallet\App\Command
 */
class CreateWalletCommand implements NamedMessage
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $coinSymbol;

    /**
     * @var string
     */
    private $walletOwnerId;

    /**
     * @var string
     */
    private $token;

    /**
     * @param string $name
     * @param string $coinSymbol
     * @param string $walletOwnerId
     * @param string $token
     */
    public function __construct($name, $coinSymbol, $walletOwnerId = null, $token = null)
    {
        $this->name = $name;
        $this->coinSymbol = $coinSymbol;
        $this->walletOwnerId = $walletOwnerId;
        $this->token = $token;
    }

    /**
     * The name of this particular name of message.
     *
     * @return string
     */
    public static function messageName()
    {
        return 'bc_app_wallet_wallet_create_wallet';
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
     * @return string
     */
    public function getWalletOwnerId()
    {
        return $this->walletOwnerId;
    }

    /**
     * @param string $walletOwnerId
     * @return $this
     */
    public function setWalletOwnerId($walletOwnerId)
    {
        $this->walletOwnerId = $walletOwnerId;
        return $this;
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param string $token
     * @return $this
     */
    public function setToken($token)
    {
        $this->token = $token;
        return $this;
    }
}