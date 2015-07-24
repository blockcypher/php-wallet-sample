<?php

namespace BlockCypher\AppCommon\App\Service\Internal;

use BlockCypher\Api\TX;
use BlockCypher\Api\TXInput;
use BlockCypher\Api\TXOutput;
use BlockCypher\Api\TXSkeleton;
use BlockCypher\AppCommon\App\Service\Internal\Exception\InvalidTransaction;
use BlockCypher\Client\TXClient;
use BlockCypher\Exception\BlockCypherConnectionException;

/**
 * Class BlockCypherTransactionService
 * @package BlockCypher\AppCommon\App\Service\Internal
 */
class BlockCypherTransactionService
{
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
     * @param string $hash
     * @param array $params
     * @param $coinSymbol
     * @param $token
     * @return TX
     */
    public function getTransaction($hash, $params, $coinSymbol, $token)
    {
        $apiContext = $this->apiContextFactory->getApiContext($coinSymbol, $token);
        $txClient = new TXClient($apiContext);

        $transaction = $txClient->get($hash, $params);

        return $transaction;
    }

    /**
     * @param string[] $hashArray
     * @param array $params
     * @param $coinSymbol
     * @param $token
     * @return TX[]
     */
    public function getTransactions($hashArray, $params, $coinSymbol, $token)
    {
        $apiContext = $this->apiContextFactory->getApiContext($coinSymbol, $token);
        $txClient = new TXClient($apiContext);

        $transaction = $txClient->getMultiple($hashArray, $params);

        return $transaction;
    }

    /**
     * @param string $walletName
     * @param string $coinSymbol
     * @param string $token
     * @param string $payToAddress
     * @param int $amount
     * @return TXSkeleton
     */
    public function create($walletName, $coinSymbol, $token, $payToAddress, $amount)
    {
        $apiContext = $this->apiContextFactory->getApiContext($coinSymbol, $token);
        $txClient = new TXClient($apiContext);

        // DEBUG
        //var_dump($amount);
        //die();

        $tx = new TX();

        // Tx inputs
        $input = new TXInput();
        $input->setWalletName($walletName);
        $input->setWalletToken($token);

        $tx->addInput($input);
        // Tx outputs
        $output = new TXOutput();
        $output->addAddress($payToAddress);
        $tx->addOutput($output);
        // Tx amount
        $output->setValue($amount); // Satoshis

        try {
            $txSkeleton = $txClient->create($tx);
        } catch (BlockCypherConnectionException $e) {

            $data = $e->getData();

            //DEBUG
            //var_export($data);
            //die();

            $txSkeleton = new TXSkeleton($data);

            //DEBUG
            //var_dump($txSkeleton);
            //die();

            throw new InvalidTransaction($txSkeleton->getAllErrorMessages());
        }

        return $txSkeleton;
    }

    /**
     * @param TXSkeleton $txSkeleton
     * @param $privateKeys
     * @param $coinSymbol
     * @param $token
     * @return TXSkeleton
     */
    public function sign(TXSkeleton $txSkeleton, $privateKeys, $coinSymbol, $token)
    {
        $apiContext = $this->apiContextFactory->getApiContext($coinSymbol, $token);
        $txClient = new TXClient($apiContext);

        $txSkeleton = $txClient->sign($txSkeleton, $privateKeys);

        return $txSkeleton;
    }

    /**
     * @param TXSkeleton $txSkeleton
     * @param $coinSymbol
     * @param $token
     * @return TXSkeleton
     */
    public function send(TXSkeleton $txSkeleton, $coinSymbol, $token)
    {
        $apiContext = $this->apiContextFactory->getApiContext($coinSymbol, $token);
        $txClient = new TXClient($apiContext);

        $txSkeleton = $txClient->send($txSkeleton);

        return $txSkeleton;
    }
}