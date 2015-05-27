<?php

namespace BlockCypher\AppCommon\App\Service;

use BlockCypher\Api\Wallet;
use BlockCypher\Api\WalletGenerateAddressResponse;

interface WalletService
{
    /**
     * @param Wallet $wallet
     * @param string $coin
     * @param string $token
     */
    public function createWallet(Wallet $wallet, $coin, $token);

    /**
     * @param string $walletName
     * @param string $coinSymbol
     * @param string $token
     * @return Wallet|null
     * @throws \Exception
     */
    public function getWallet($walletName, $coinSymbol, $token);

    /**
     * @param string $walletName
     * @param string $coinSymbol
     * @param string $token
     * @return WalletGenerateAddressResponse
     */
    public function generateAddress($walletName, $coinSymbol, $token);
}