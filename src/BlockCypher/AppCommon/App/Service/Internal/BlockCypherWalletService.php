<?php

namespace BlockCypher\AppCommon\App\Service\Internal;

use BlockCypher\Api\Address;
use BlockCypher\Api\Wallet;
use BlockCypher\Api\WalletGenerateAddressResponse;
use BlockCypher\AppCommon\App\Service\WalletService;
use BlockCypher\AppCommon\Domain\BigMoney;
use BlockCypher\Core\BlockCypherCoinSymbolConstants;
use BlockCypher\Exception\BlockCypherConnectionException;
use Money\Currency;

class BlockCypherWalletService implements WalletService
{
    const ERROR_WALLET_NOT_FOUND = 404;

    /**
     * @param BlockCypherApiContextFactory $apiContextFactory
     */
    public function __construct(BlockCypherApiContextFactory $apiContextFactory)
    {
        $this->apiContextFactory = $apiContextFactory;
    }

    /**
     * @param Wallet $wallet
     * @param string $coin
     * @param string $token
     */
    public function createWallet(Wallet $wallet, $coin, $token)
    {
        // TODO: extract to field?
        $apiContext = $this->apiContextFactory->getApiContext($token);

        $wallet->create(array(), $apiContext);
    }

    /**
     * @param string $walletName
     * @param string $coinSymbol
     * @param string $token
     * @return Wallet|null
     * @throws BlockCypherConnectionException
     * @throws \Exception
     */
    public function getWallet($walletName, $coinSymbol, $token)
    {
        // TODO: extract to field?
        $apiContext = $this->apiContextFactory->getApiContext($token);

        $wallet = null;

        try {
            $wallet = Wallet::get($walletName, array(), $apiContext);
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
     * @param string $walletName
     * @param string $coinSymbol
     * @param string $token
     * @return BigMoney
     * @throws \Exception
     */
    public function getWalletBalance($walletName, $coinSymbol, $token)
    {
        // TODO: extract to field?
        $apiContext = $this->apiContextFactory->getApiContext($token);

        $balance = null;

        try {
            // TODO: wallet balance is obtained from this API endpoint /addrs/<wallet_name>/balance
            // Change to Wallet::getBalance when /wallets/<wallet_name>/balance endpoint is implemented

            $address = Address::get($walletName, array(), $apiContext);
            $currency = new Currency(BlockCypherCoinSymbolConstants::getCurrencyAbbrev($coinSymbol));
            $balance = BigMoney::fromInteger($address->getTotalReceived(), $currency);

        } catch (BlockCypherConnectionException $e) {
            if ($e->getCode() == self::ERROR_WALLET_NOT_FOUND) {
                // return null
            } else {
                throw $e;
            }
        }

        return $balance;
    }

    /**
     * @param string $walletName
     * @param string $coinSymbol
     * @param string $token
     * @return WalletGenerateAddressResponse
     */
    public function generateAddress($walletName, $coinSymbol, $token)
    {
        // TODO: extract to field?
        $apiContext = $this->apiContextFactory->getApiContext($token);

        $wallet = Wallet::get($walletName, array(), $apiContext);
        $walletGenerateAddressResponse = $wallet->generateAddress(array(), $apiContext);

        return $walletGenerateAddressResponse;
    }
}