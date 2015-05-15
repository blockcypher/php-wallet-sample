<?php

namespace BlockCypher\AppCommon\App\Service\Internal;

use BlockCypher\Auth\SimpleTokenCredential;
use BlockCypher\Rest\ApiContext;

class BlockCypherApiContextFactory extends ApiContext
{
    /**
     *
     */
    function __construct()
    {
        // TODO: add parameters: maybe sdk_config path or settings from parameters.yml
        // All settings are hardcoded for the time being
    }

    /**
     * @param string $token
     * @param string $chain
     * @param string $coin
     * @param string $version
     * @return ApiContext
     */
    public function getApiContext($token, $chain = 'main', $coin = 'btc', $version = 'v1')
    {
        // TODO: validate parameters

        // TODO: apiContext instances cache?

        return $this->getApiContextUsingConfigArray($token, $chain, $coin, $version);
    }

    /**
     * Helper method for getting an APIContext for all calls (getting config from array)
     * @param string $token
     * @param string $version v1
     * @param string $coin btc|doge|ltc|uro|bcy
     * @param string $chain main|test3|test
     * @return ApiContext
     */
    private function getApiContextUsingConfigArray($token, $chain = 'main', $coin = 'btc', $version = 'v1')
    {
        $credentials = new SimpleTokenCredential($token);

        $config = array(
            'mode' => 'sandbox',
            'log.LogEnabled' => true,
            'log.FileName' => '../BlockCypher.log',
            'log.LogLevel' => 'DEBUG', // PLEASE USE `INFO` LEVEL FOR LOGGING IN LIVE ENVIRONMENTS
            'validation.level' => 'log',
            // 'http.CURLOPT_CONNECTTIMEOUT' => 30
        );

        $apiContext = ApiContext::create($chain, $coin, $version, $credentials, $config);

        ApiContext::setDefault($apiContext);

        return $apiContext;
    }

    /**
     * Helper method for getting an APIContext for all calls (getting config from ini file)
     * @return \BlockCypher\Rest\ApiContext
     */
    private function getApiContextUsingConfigIni()
    {
        // #### SDK configuration
        // Register the sdk_config.ini file in current directory
        // as the configuration source.
        if (!defined("BC_CONFIG_PATH")) {
            define("BC_CONFIG_PATH", __DIR__);
        }

        $apiContext = ApiContext::create('main', 'btc', 'v1');

        return $apiContext;
    }

    /**
     * @param string $token
     * @return bool
     */
    private function validateToken($token)
    {
        // TODO: this function should be moved to the SDK

        // sample tokens:
        // c0afcccdde5081d6429de37d16166ead
        // ddf3g04f-0f31-4060-978b-63b1ff43e185

        if (strlen($token) < 20) return false;
        if (strlen($token) > 50) return false;
        if (!preg_match('/[a-z0-9-]+/', $token)) return false;

        return true;
    }
}