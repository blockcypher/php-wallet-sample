<?php

namespace BlockCypher\AppCommon\App\Service\Internal;

use BlockCypher\Api\Wallet;
use BlockCypher\Api\WalletGenerateAddressResponse;
use BlockCypher\AppCommon\App\Service\WalletService;
use BlockCypher\Exception\BlockCypherConnectionException;

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