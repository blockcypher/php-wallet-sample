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
     * @param $name
     * @param string $coinSymbol
     */
    public function __construct($name, $coinSymbol)
    {
        $this->name = $name;
        $this->coinSymbol = $coinSymbol;
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
}