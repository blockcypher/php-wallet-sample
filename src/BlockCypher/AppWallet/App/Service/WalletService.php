<?php

namespace BlockCypher\AppWallet\App\Service;

use BlockCypher\AppCommon\App\Service\Internal\BlockCypherWalletService;
use BlockCypher\AppWallet\Domain\Address\Address;
use BlockCypher\AppWallet\Domain\Address\AddressRepository;
use BlockCypher\AppWallet\Domain\Transaction\Transaction;
use BlockCypher\AppWallet\Domain\Transaction\TransactionRepository;
use BlockCypher\AppWallet\Domain\Wallet\Wallet;
use BlockCypher\AppWallet\Domain\Wallet\WalletId;
use BlockCypher\AppWallet\Domain\Wallet\WalletRepository;
use Money\BigMoney;

class WalletService
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
     * @var BlockCypherWalletService
     */
    private $blockCypherWalletService;

    /**
     * Constructor
     * @param WalletRepository $walletRepository
     * @param AddressRepository $addressRepository
     * @param TransactionRepository $transactionRepository
     * @param BlockCypherWalletService $blockCypherWalletService
     */
    public function __construct(
        WalletRepository $walletRepository,
        AddressRepository $addressRepository,
        TransactionRepository $transactionRepository,
        BlockCypherWalletService $blockCypherWalletService
    )
    {
        $this->walletRepository = $walletRepository;
        $this->addressRepository = $addressRepository;
        $this->transactionRepository = $transactionRepository;
        $this->blockCypherWalletService = $blockCypherWalletService;
    }

    /**
     * @param WalletId $walletId
     * @return BigMoney
     * @throws \Exception
     */
    public function getWalletBalance(WalletId $walletId)
    {
        $wallet = $this->walletRepository->walletOfId($walletId);

        $balance = $this->blockCypherWalletService->getWalletFinalBalance(
            $wallet->getId()->getValue(),
            $wallet->getCoinSymbol(),
            $wallet->getToken()
        );

        return $balance;
    }

    /**
     * Get addresses from external wallet and add them to the current wallet.
     * @param WalletId $walletId
     * @throws \Exception
     */
//    public function syncAddressesFromWalletService(WalletId $walletId)
//    {
//        $wallet = $this->walletRepository->walletOfId($walletId);
//
//        $blockCypherWallet = $this->blockCypherWalletService->getWallet(
//            $wallet->getId()->getValue(),
//            $wallet->getCoinSymbol(),
//            $wallet->getToken()
//        );
//
//        if ($blockCypherWallet === null) {
//            // TODO: custom domain exception
//            throw new \Exception(sprintf("Wallet not found in external service"));
//        }
//
//        $cont = 0;
//        $externalWalletAddresses = array();
//        if (is_array($blockCypherWallet->getAddresses())) {
//            // if external wallet has no addresses getAddresses method will return null
//            $externalWalletAddresses = $blockCypherWallet->getAddresses();
//        }
//        foreach ($externalWalletAddresses as $externalAddress) {
//            if (!$wallet->containsAddress($externalAddress)) {
//                $tag = "Imported Address from API #$cont";
//                $address = new Address(
//                    $externalAddress,
//                    $wallet->getId(),
//                    $wallet->getCreationTime(),
//                    $tag,
//                    '',
//                    $externalAddress,
//                    '',
//                    ''
//                );
//
//                $wallet->addAddress($address);
//            }
//
//            $cont++;
//        }
//    }

    /**
     * @param WalletId $walletId
     * @return \string[]
     */
    public function getAddresses(WalletId $walletId)
    {
        $wallet = $this->walletRepository->walletOfId($walletId);

        $addresses = $this->blockCypherWalletService->getWalletAddresses(
            $wallet->getId()->getValue(),
            $wallet->getCoinSymbol(),
            $wallet->getToken()
        );

        return $addresses;
    }

    /**
     * @return Wallet[]
     */
    public function listWallets()
    {
        $wallets = $this->walletRepository->findAll();
        return $wallets;
    }

    /**
     * @param WalletId $walletId
     * @return Address[]
     */
    public function listWalletAddresses(WalletId $walletId)
    {
        // DEBUG
        //$addresses = $this->addressRepository->addressesOfWalletId($walletId);
        //var_dump($walletId);
        //var_dump($addresses);
        //die();

        return $this->addressRepository->addressesOfWalletId($walletId);
    }

    /**
     * @param WalletId $walletId
     * @return Wallet
     */
    public function getWallet(WalletId $walletId)
    {
        $wallet = $this->walletRepository->walletOfId($walletId);
        return $wallet;
    }

    /**
     * @param WalletId $walletId
     * @return Transaction[]
     */
    public function listWalletTransactions(WalletId $walletId)
    {
        return $this->transactionRepository->transactionsOfWalletId($walletId);
    }
}