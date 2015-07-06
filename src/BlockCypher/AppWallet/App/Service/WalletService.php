<?php

namespace BlockCypher\AppWallet\App\Service;

use BlockCypher\AppCommon\App\Service\Internal\BlockCypherWalletService;
use BlockCypher\AppCommon\Domain\BigMoney;
use BlockCypher\AppWallet\Domain\Address\Address;
use BlockCypher\AppWallet\Domain\Wallet\Wallet;
use BlockCypher\AppWallet\Domain\Wallet\WalletId;
use BlockCypher\AppWallet\Domain\Wallet\WalletRepository;

class WalletService
{
    /**
     * @var WalletRepository
     */
    private $walletRepository;

    /**
     * @var BlockCypherWalletService
     */
    private $blockCypherWalletService;

    /**
     * Constructor
     * @param WalletRepository $walletRepository
     * @param BlockCypherWalletService $blockCypherWalletService
     */
    public function __construct(
        WalletRepository $walletRepository,
        BlockCypherWalletService $blockCypherWalletService
    )
    {
        $this->walletRepository = $walletRepository;
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

        $balance = $this->blockCypherWalletService->getWalletBalance(
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
    public function syncAddressesFromWalletService(WalletId $walletId)
    {
        $wallet = $this->walletRepository->walletOfId($walletId);

        $blockCypherWallet = $this->blockCypherWalletService->getWallet(
            $wallet->getId()->getValue(),
            $wallet->getCoinSymbol(),
            $wallet->getToken()
        );

        if ($blockCypherWallet === null) {
            // TODO: custom domain exception
            throw new \Exception(sprintf("Wallet not found in external service"));
        }

        $cont = 0;
        $externalWalletAddresses = array();
        if (is_array($blockCypherWallet->getAddresses())) {
            // if external wallet has no addresses getAddresses method will return null
            $externalWalletAddresses = $blockCypherWallet->getAddresses();
        }
        foreach ($externalWalletAddresses as $externalAddress) {
            if (!$wallet->containsAddress($externalAddress)) {
                $tag = "Imported Address from API #$cont";
                $address = new Address(
                    $externalAddress,
                    $wallet->getId(),
                    $wallet->getCreationTime(),
                    $tag,
                    '',
                    $externalAddress,
                    '',
                    ''
                );

                $wallet->addAddress($address);
            }

            $cont++;
        }
    }

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
        $wallet = $this->walletRepository->walletOfId($walletId);
        return $wallet->getAddresses();
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
}