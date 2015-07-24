<?php

namespace BlockCypher\AppWallet\App\Command;

use BitWasp\Bitcoin\Key\PrivateKeyFactory;
use BlockCypher\AppCommon\App\Service\Clock;
use BlockCypher\AppCommon\App\Service\Internal\BlockCypherTransactionService;
use BlockCypher\AppWallet\Domain\Address\AddressRepository;
use BlockCypher\AppWallet\Domain\Transaction\Transaction;
use BlockCypher\AppWallet\Domain\Transaction\TransactionRepository;
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
        $walletId = $command->getWalletId();
        $payToAddress = $command->getPayToAddress();
        $description = $command->getDescription();
        $amount = $command->getAmount();

        // Get wallet object from repository
        $wallet = $this->walletRepository->walletOfId(new WalletId($walletId));

        if (!$wallet) {
            throw new \Exception(sprintf("Wallet not found %s", $walletId));
        }

        // Call BlockCypher API to generate new transaction
        $txSkeleton = $this->blockCypherTransactionService->create(
            $wallet->getId()->getValue(),
            $wallet->getCoinSymbol(),
            $wallet->getToken(),
            $payToAddress,
            $amount
        );

        // Create new app Transaction
        $transaction = new Transaction(
            $this->transactionRepository->nextIdentity(),
            $wallet->getId(),
            null,
            $payToAddress,
            $description,
            $amount,
            $this->clock->now()
        );

        // Get all addresses from all tx inputs.
        $allInputsAddresses = $txSkeleton->getInputsAddresses();

        // Get private keys from repository
        $privateKeys = $this->getPrivateKeysFromRepository($allInputsAddresses, $walletId);

        // Check private keys
        $this->checkPrivateKeys($privateKeys, $txSkeleton->getTosign());

        // Sign transaction
        $txSkeletonSigned = $this->blockCypherTransactionService->sign($txSkeleton, $privateKeys, $wallet->getCoinSymbol(), $wallet->getToken());

        // Send transaction to the network
        $txSkeletonSent = $this->blockCypherTransactionService->send($txSkeletonSigned, $wallet->getCoinSymbol(), $wallet->getToken());

        // Map real network tx with app transaction
        $transaction->assignNetworkTransactionHash($txSkeletonSent->getTx()->getHash());

        // Store new local app transaction
        $this->transactionRepository->insert($transaction);
    }

    /**
     * @param $allInputsAddresses
     * @param $walletId
     * @return array
     * @throws \Exception
     */
    private function getPrivateKeysFromRepository($allInputsAddresses, $walletId)
    {
        // TODO: Code Review. Support for multisign addresses.
        // PrivateKeys are stored in Address object. They should be stored in a new Key class.
        // Multisign addresses have more than one key pair.

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

    /**
     * @param string[] $privateKeys
     * @param string[] $tosign
     * @throws \Exception
     */
    private function checkPrivateKeys($privateKeys, $tosign)
    {
        if (!is_array($privateKeys)) {
            throw new \Exception("Invalid private keys format. Array expected.");
        }

        if (count($privateKeys) !== count($tosign)) {
            throw new \Exception("Missing private keys");
        }

        foreach ($privateKeys as $privateKey) {
            $this->checkHexPrivateKey($privateKey);
        }
    }

    /**
     * @param string $privateKey
     * @throws \Exception
     */
    private function checkHexPrivateKey($privateKey)
    {
        if (!is_string($privateKey)) {
            throw new \Exception("Invalid private key format. String expected.");
        }

        try {
            PrivateKeyFactory::fromHex($privateKey);
        } catch (\Exception $e) {
            throw new \Exception("Invalid private key format. Hex format expected.");
        }
    }
}