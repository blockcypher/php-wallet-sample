<?php

namespace BlockCypher\AppWallet\App\Command;

use Assert\Assertion;
use SimpleBus\Message\Name\NamedMessage;

/**
 * Class FundAddressCommand
 * @package BlockCypher\AppWallet\App\Command
 */
class FundAddressCommand implements NamedMessage
{
    /**
     * @var string
     */
    private $address;

    /**
     * @var int
     */
    private $amount;

    /**
     * @var string
     */
    private $coinSymbol;

    /**
     * @var string
     */
    private $token;

    /**
     * @param string $address
     * @param int $amount
     * @param string $coinSymbol
     * @param string $token
     */
    public function __construct($address, $amount, $coinSymbol, $token = null)
    {
        Assertion::integer($amount);
        $this->address = $address;
        $this->coinSymbol = $coinSymbol;
        $this->token = $token;
    }

    /**
     * The name of this particular name of message.
     *
     * @return string
     */
    public static function messageName()
    {
        return 'bc_app_wallet_faucet_fund_address';
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
        $this->amount = $amount;
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