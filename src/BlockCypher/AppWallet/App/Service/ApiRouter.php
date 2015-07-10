<?php

namespace BlockCypher\AppWallet\App\Service;

use BlockCypher\Core\BlockCypherCoinSymbolConstants;

/**
 * Class ApiRouter
 * @package BlockCypher\AppWallet\App\Service
 */
class ApiRouter
{
    /**
     * @param string $address
     * @param string $coinSymbol
     * @param string $token
     * @return string
     */
    public function address($address, $coinSymbol, $token)
    {
        // https://api.blockcypher.com/v1/btc/test3/addrs/mwJJYj6BNfUTufsGKNYcm3T46F6Ai1e3nM

        $coin = BlockCypherCoinSymbolConstants::getBlockCypherCode($coinSymbol);
        $chain = BlockCypherCoinSymbolConstants::getBlockCypherNetwork($coinSymbol);

        $apiUrl = "https://api.blockcypher.com/v1/$coin/$chain/addrs/$address?token=$token";

        return $apiUrl;
    }

    /**
     * @param string $txHash
     * @param string $coinSymbol
     * @param string $token
     * @return string
     */
    public function transaction($txHash, $coinSymbol, $token)
    {
        // https://api.blockcypher.com/v1/btc/test3/txs/a5f838cf2ce8546c838e9a04210d8af7b390412743ed1daaecbb20df4a9745fe

        $coin = BlockCypherCoinSymbolConstants::getBlockCypherCode($coinSymbol);
        $chain = BlockCypherCoinSymbolConstants::getBlockCypherNetwork($coinSymbol);

        $apiUrl = "https://api.blockcypher.com/v1/$coin/$chain/txs/$txHash?token=$token";

        return $apiUrl;
    }

    /**
     * @param string $walletName
     * @param string $coinSymbol
     * @param string $token
     * @return string
     * @throws \BlockCypher\Exception\BlockCypherConfigurationException
     */
    public function wallet($walletName, $coinSymbol, $token)
    {
        // http://api.blockcypher.com/v1/btc/test3/addrs/559AC33394F28292099183?token=c0afcccdde5081d6429de37d16166ead

        $coin = BlockCypherCoinSymbolConstants::getBlockCypherCode($coinSymbol);
        $chain = BlockCypherCoinSymbolConstants::getBlockCypherNetwork($coinSymbol);

        $apiUrl = "https://api.blockcypher.com/v1/$coin/$chain/addrs/$walletName?token=$token";

        return $apiUrl;
    }
}