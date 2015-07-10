<?php

namespace BlockCypher\AppWallet\App\Service;

/**
 * Class ExplorerRouter
 * @package BlockCypher\AppWallet\App\Service
 */
class ExplorerRouter
{
    /**
     * @param string $address
     * @param string $coinSymbol
     * @return string
     * @throws \BlockCypher\Exception\BlockCypherConfigurationException
     */
    public function address($address, $coinSymbol)
    {
        // https://live.blockcypher.com/btc-testnet/address/mwJJYj6BNfUTufsGKNYcm3T46F6Ai1e3nM/

        $explorerUrl = "https://live.blockcypher.com/$coinSymbol/address/$address";

        return $explorerUrl;
    }

    /**
     * @param string $txHash
     * @param string $coinSymbol
     * @return string
     */
    public function transaction($txHash, $coinSymbol)
    {
        // https://live.blockcypher.com/btc-testnet/tx/a5f838cf2ce8546c838e9a04210d8af7b390412743ed1daaecbb20df4a9745fe/

        $explorerUrl = "https://live.blockcypher.com/$coinSymbol/tx/$txHash";

        return $explorerUrl;
    }
}