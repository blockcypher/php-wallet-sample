<?php

namespace BlockCypher\AppCommon\App\Service\Internal;

use BlockCypher\Auth\SimpleTokenCredential;
use BlockCypher\Core\BlockCypherCoinSymbolConstants;
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
     * @param $coinSymbol
     * @param $token
     * @return \BlockCypher\Rest\ApiContext
     * @throws \BlockCypher\Exception\BlockCypherConfigurationException
     */
    public function getApiContext($coinSymbol, $token)
    {
        $coin = BlockCypherCoinSymbolConstants::getBlockCypherCode($coinSymbol);
        $chain = BlockCypherCoinSymbolConstants::getBlockCypherNetwork($coinSymbol);

        $apiContext = $this->getApiContextUsingConfigArray($token, $chain, $coin);

        return $apiContext;
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
            'log.FileName' => '../app/logs/BlockCypher.log',
            'log.LogLevel' => 'DEBUG', // PLEASE USE `INFO` LEVEL FOR LOGGING IN LIVE ENVIRONMENTS
            //'validation.level' => 'log',
            'validation.level' => 'disable',
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
//    private function getApiContextUsingConfigIni()
//    {
//        // #### SDK configuration
//        // Register the sdk_config.ini file in current directory
//        // as the configuration source.
//        if (!defined("BC_CONFIG_PATH")) {
//            define("BC_CONFIG_PATH", __DIR__);
//        }
//
//        $apiContext = ApiContext::create('main', 'btc', 'v1');
//
//        return $apiContext;
//    }
}