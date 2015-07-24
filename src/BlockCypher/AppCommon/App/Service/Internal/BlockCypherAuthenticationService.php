<?php

namespace BlockCypher\AppCommon\App\Service\Internal;

use BlockCypher\Api\Wallet as BlockCypherWallet;
use BlockCypher\Client\WalletClient;
use BlockCypher\Exception\BlockCypherConnectionException;

/**
 * Class BlockCypherAuthenticationService
 * @package BlockCypher\AppCommon\App\Service\Internal
 */
class BlockCypherAuthenticationService
{
    const ERROR_WALLET_NOT_FOUND = 404;
    const ERROR_WALLET_ALREADY_EXISTS = 409;
    const AUTH_WALLET_COIN_SYMBOL = 'btc-testnet';
    const AUTH_WALLET_NAME = 'AUTH_WALLET';

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
     * @param string $token
     * @return bool
     */
    public function authenticate($token)
    {
        $apiContext = $this->apiContextFactory->getApiContext(self::AUTH_WALLET_COIN_SYMBOL, $token);
        $walletClient = new WalletClient($apiContext);

        try {
            // Create BlockCypher wallet
            $bcWallet = new BlockCypherWallet();
            $bcWallet->setToken($token);
            $bcWallet->setName(self::AUTH_WALLET_NAME);

            $bcWallet = $walletClient->create($bcWallet);

            if ($bcWallet && $bcWallet->getName() == self::AUTH_WALLET_NAME) {
                return true;
            } else {
                return false;
            }

        } catch (BlockCypherConnectionException $e) {
            if ($e->getCode() == self::ERROR_WALLET_ALREADY_EXISTS) {
                return true;
            } else {
                return false;
            }
        } catch (\Exception $e) {
            return false;
        }
    }
}