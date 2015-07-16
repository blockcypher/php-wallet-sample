<?php

namespace BlockCypher\AppCommon\App\Service\Internal;

use BlockCypher\Api\TX;
use BlockCypher\Api\TXInput;
use BlockCypher\Api\TXOutput;
use BlockCypher\Api\TXSkeleton;
use BlockCypher\AppCommon\App\Service\Internal\Exception\InvalidTransaction;
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

        $transaction = TX::get($hash, $params, $apiContext);

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

        $transaction = TX::getMultiple($hashArray, $params, $apiContext);

        return $transaction;
    }

    public function create($walletName, $coinSymbol, $token, $payToAddress, $amount)
    {
        $apiContext = $this->apiContextFactory->getApiContext($coinSymbol, $token);

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
            $txSkeleton = $tx->create($apiContext);
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
}