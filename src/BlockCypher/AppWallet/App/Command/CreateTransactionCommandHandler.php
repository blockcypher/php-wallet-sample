<?php

namespace BlockCypher\AppWallet\App\Command;

use BlockCypher\Api\TXSkeleton;
use BlockCypher\AppCommon\App\Service\Clock;
use BlockCypher\AppCommon\App\Service\Internal\BlockCypherTransactionService;
use BlockCypher\AppWallet\Domain\Address\AddressRepository;
use BlockCypher\AppWallet\Domain\Transaction\Transaction;
use BlockCypher\AppWallet\Domain\Transaction\TransactionRepository;
use BlockCypher\AppWallet\Domain\Wallet\Wallet;
use BlockCypher\AppWallet\Domain\Wallet\WalletId;
use BlockCypher\AppWallet\Domain\Wallet\WalletRepository;

/**
 * Class CreateTransactionCommandHandler
 * @package BlockCypher\AppWallet\App\Command
 */
class CreateTransactionCommandHandler
{
    /**
     * @var WalletRepository
     */
    private $walletRepository;

    /**
     * @var AddressRepository
     */
    private $addressRepository;

    /**
     * @var TransactionRepository
     */
    private $transactionRepository;

    /**
     * @var BlockCypherTransactionService
     */
    private $blockCypherTransactionService;

    /**
     * @var Clock
     */
    private $clock;

    /**
     * Constructor
     * @param WalletRepository $walletRepository
     * @param AddressRepository $addressRepository
     * @param TransactionRepository $transactionRepository
     * @param BlockCypherTransactionService $blockCypherTransactionService
     * @param Clock $clock
     */
    public function __construct(
        WalletRepository $walletRepository,
        AddressRepository $addressRepository,
        TransactionRepository $transactionRepository,
        BlockCypherTransactionService $blockCypherTransactionService,
        Clock $clock
    )
    {
        $this->walletRepository = $walletRepository;
        $this->addressRepository = $addressRepository;
        $this->transactionRepository = $transactionRepository;
        $this->blockCypherTransactionService = $blockCypherTransactionService;
        $this->clock = $clock;
    }

    /**
     * @param CreateTransactionCommand $command
     * @throws \Exception
     */
    public function handle(CreateTransactionCommand $command)
    {
        // DEBUG
        //var_dump($command);

        $commandValidator = new CreateTransactionCommandValidator();
        $commandValidator->validate($command);

        $walletId = $command->getWalletId();
        $payToAddress = $command->getPayToAddress();
        $description = $command->getDescription();
        $amount = $command->getAmount();

        // DEBUG
        //var_dump($command);
        //die();

        $wallet = $this->walletRepository->walletOfId(new WalletId($walletId));

        // DEBUG
        //var_dump($wallet);
        //die();

        if ($wallet === null) {
            // TODO: create domain exception
            throw new \Exception(sprintf("Wallet not found %s", $walletId));
        }

        // 1.- Call BlockCypher API to generate new transaction
        $txSkeleton = $this->blockCypherTransactionService->create(
            $wallet->getId()->getValue(),
            $wallet->getCoinSymbol(),
            $wallet->getToken(),
            $payToAddress,
            $amount
        );

        // DEBUG
        //echo "CreateTransactionCommandHandler::handle";
        //var_export($txSkeleton->toJson());
        //die();

        // 2.- Create new app Transaction
        $transaction = new Transaction(
            $this->transactionRepository->nextIdentity(),
            $wallet->getId(),
            null,
            $payToAddress,
            $description,
            $amount,
            $this->clock->now()
        );

        // DEBUG
        //var_dump($transaction);
        //die();

        // 3.- Sign transaction

        // Patch: there is bug in current API version. Tx skeleton does not contains addresses in inputs
        // when a wallet is used in the transaction creation endpoint. It's going to be fixed soon.
        $this->patchInputsAddresses($txSkeleton, $wallet);

        // Get all addresses from all tx inputs.
        $allInputsAddresses = $txSkeleton->getInputsAddresses();

        // DEBUG
        //var_dump($allInputsAddresses);
        //die();

        $privateKeys = $this->getPrivateKeysFromRepository($allInputsAddresses, $walletId);

        // DEBUG
        //var_dump($allInputsAddresses);
        //var_dump($privateKeys);
        //die();

        $txSkeleton->sign($privateKeys);

        // DEBUG
        //var_dump($txSkeleton);
        //die();

        // 4.- Send transaction
        $txSkeleton->send();

        // 5.- Map real network tx with app transaction
        $transaction->assignNetworkTransactionHash($txSkeleton->getTx()->getHash());

        // 6.- Store new local app transaction
        $this->transactionRepository->insert($transaction);
    }

    /**
     * Current REST API version does not return addresses attribute in TXInputs when a wallet is used
     * in Create Transaction Endpoint. We get from unspent outputs from the previous tx hash.
     *
     * @param TXSkeleton $txSkeleton
     * @param Wallet $wallet
     * @return array
     */
    private function patchInputsAddresses(TXSkeleton $txSkeleton, Wallet $wallet)
    {
        $allInputsAddresses = array();
        foreach ($txSkeleton->getTx()->getInputs() as $txInput) {

            //DEBUG
            //var_dump($txInput->getAddresses());
            //die();

            if (!is_array($txInput->getAddresses()) || count($txInput->getAddresses()) == 0) {

                // Get previous tx for this input
                $tx = $this->blockCypherTransactionService->getTransaction(
                    $txInput->getPrevHash(),
                    array(),
                    $wallet->getCoinSymbol(),
                    $wallet->getToken()
                );

                // DEBUG
                //var_dump($tx);
                //die();

                $txOutputs = $tx->getOutputs();

                // DEBUG
                //var_dump($txOutputs);
                //die();

                // Collect all outputs addresses
                $previousTxOutputsAddresses = array();
                foreach ($txOutputs as $txOutput) {
                    if ($txOutput->getSpentBy() === null) {
                        // UTXO
                        $previousTxOutputsAddresses = array_merge($previousTxOutputsAddresses, $txOutput->getAddresses());
                    }
                }

                $inputAddresses = $previousTxOutputsAddresses;
                $txInput->setAddresses($inputAddresses);

            } else {
                $inputAddresses = $txInput->getAddresses();
            }

            $allInputsAddresses = array_merge($allInputsAddresses, $inputAddresses);
        }

        return $allInputsAddresses;
    }

    /**
     * @param $allInputsAddresses
     * @param $walletId
     * @return array
     * @throws \Exception
     */
    private function getPrivateKeysFromRepository($allInputsAddresses, $walletId)
    {
        $privateKeys = array();
        foreach ($allInputsAddresses as $addressInTransaction) {

            $address = $this->addressRepository->addressOfWalletId($addressInTransaction, new WalletId($walletId));

            // DEBUG
            //echo "AddressInTransaction: $addressInTransaction";
            //var_dump($address->getAddress());

            if ($address === null) {
                throw new\Exception(sprintf("Address %s not found in wallet %s", $addressInTransaction, $walletId));
            }

            if ($address !== null) {
                $privateKeys[$address->getAddress()] = $address->getPrivate();
            }
        }
        return $privateKeys;
    }
}