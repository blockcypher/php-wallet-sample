<?php

namespace BlockCypher\AppCommon\App\Service\Internal;

use BlockCypher\Api\Wallet as BlockCypherWallet;
use BlockCypher\Api\WalletGenerateAddressResponse;
use BlockCypher\Client\AddressClient;
use BlockCypher\Client\WalletClient;
use BlockCypher\Core\BlockCypherCoinSymbolConstants;
use BlockCypher\Exception\BlockCypherConnectionException;
use Money\Money;
use Money\Currency;

/**
 * Class BlockCypherWalletService
 * @package BlockCypher\AppCommon\App\Service\Internal
 */
class BlockCypherWalletService
{
    const ERROR_WALLET_NOT_FOUND = 404;

    /**
     * @var BlockCypherApiContextFactory
     */
    private $apiContextFactory;

    /**
     * @param BlockCypherApiContextFactory $apiContextFactory
     */
    public function __construct(BlockCypherApiContextFactory $apiContextFactory)
    {
        $this->apiContextFactory = $apiContextFactory;
    }

    /**
     * @param $walletName
     * @param $coinSymbol
     * @param $token
     * @return BlockCypherWallet
     */
    public function createWallet($walletName, $coinSymbol, $token)
    {
        $apiContext = $this->apiContextFactory->getApiContext($coinSymbol, $token);
        $walletClient = new WalletClient($apiContext);

        // Create BlockCypher wallet
        $bcWallet = new BlockCypherWallet();
        $bcWallet->setToken($token);
        $bcWallet->setName($walletName);

        return $walletClient->create($bcWallet);
    }

    /**
     * @param $walletName
     * @param $coinSymbol
     * @param $token
     * @return BlockCypherWallet|null
     * @throws BlockCypherConnectionException
     * @throws \Exception
     */
    public function getWallet($walletName, $coinSymbol, $token)
    {
        $apiContext = $this->apiContextFactory->getApiContext($coinSymbol, $token);
        $walletClient = new WalletClient($apiContext);

        $wallet = null;

        try {
            $wallet = $walletClient->get($walletName);
        } catch (BlockCypherConnectionException $e) {
            if ($e->getCode() == self::ERROR_WALLET_NOT_FOUND) {
                // return null
            } else {
                throw $e;
            }
        }

        return $wallet;
    }

    /**
     * @param $walletName
     * @param $coinSymbol
     * @param $token
     * @return Money|null
     * @throws BlockCypherConnectionException
     * @throws \BlockCypher\Exception\BlockCypherConfigurationException
     * @throws \Exception
     */
    public function getWalletFinalBalance($walletName, $coinSymbol, $token)
    {
        $apiContext = $this->apiContextFactory->getApiContext($coinSymbol, $token);
        $addressClient = new AddressClient($apiContext);

        $balance = null;
        $address = null;

        try {
            $address = $addressClient->get($walletName);
        } catch (BlockCypherConnectionException $e) {
            if ($e->getCode() == self::ERROR_WALLET_NOT_FOUND) {
                // return null
            } else {
                throw $e;
            }
        }

        if ($address !== null) {
            $currencyAbbrev = BlockCypherCoinSymbolConstants::getCurrencyAbbrev($coinSymbol);
            $currency = new Currency($currencyAbbrev);
            $balance = new Money($address->getFinalBalance(), $currency);
        }

        return $balance;
    }

    /**
     * @param $walletName
     * @param $coinSymbol
     * @param $token
     * @return WalletGenerateAddressResponse
     */
    public function generateAddress($walletName, $coinSymbol, $token)
    {
        $apiContext = $this->apiContextFactory->getApiContext($coinSymbol, $token);
        $walletClient = new WalletClient($apiContext);

        $walletGenerateAddressResponse = $walletClient->generateAddress($walletName);

        return $walletGenerateAddressResponse;
    }

    /**
     * @param $walletName
     * @param $coinSymbol
     * @param $token
     * @return \string[]
     */
    public function getWalletAddresses($walletName, $coinSymbol, $token)
    {
        $apiContext = $this->apiContextFactory->getApiContext($coinSymbol, $token);
        $walletClient = new WalletClient($apiContext);

        $addressList = $walletClient->getWalletAddresses($walletName);
        $addresses = $addressList->getAddresses();

        return $addresses;
    }
}