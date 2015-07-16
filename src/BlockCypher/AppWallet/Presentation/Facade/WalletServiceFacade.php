<?php

namespace BlockCypher\AppWallet\Presentation\Facade;

use BlockCypher\Api\Address as BlockCypherAddress;
use BlockCypher\Api\AddressBalance as BlockCypherAddressBalance;
use BlockCypher\AppCommon\App\Service\Internal\BlockCypherAddressService;
use BlockCypher\AppCommon\App\Service\Internal\BlockCypherWalletService;
use BlockCypher\AppCommon\Domain\User\UserId;
use BlockCypher\AppWallet\App\Service\ApiRouter;
use BlockCypher\AppWallet\App\Service\ExplorerRouter;
use BlockCypher\AppWallet\App\Service\WalletService;
use BlockCypher\AppWallet\Domain\Wallet\Wallet;
use BlockCypher\AppWallet\Domain\Wallet\WalletId;
use BlockCypher\AppWallet\Presentation\Facade\Dto\AddressListItemDto;
use BlockCypher\AppWallet\Presentation\Facade\Dto\AddressListItemDtoArray;
use BlockCypher\AppWallet\Presentation\Facade\Dto\TransactionListDto;
use BlockCypher\AppWallet\Presentation\Facade\Dto\WalletDto;
use BlockCypher\AppWallet\Presentation\Facade\Dto\WalletListItemDto;
use BlockCypher\AppWallet\Presentation\Facade\Dto\WalletListItemDtoArray;
use Money\BigMoney;

/**
 * Class WalletServiceFacade
 * @package BlockCypher\AppWallet\Presentation\Facade
 */
class WalletServiceFacade
{
    /**
     * @var WalletService
     */
    private $walletService;

    /**
     * @var BlockCypherWalletService
     */
    private $blockCypherWalletService;

    /**
     * @param WalletService $walletService
     * @param BlockCypherWalletService $blockCypherWalletService
     * @param BlockCypherAddressService $blockCypherAddressService
     */
    function __construct(
        WalletService $walletService,
        BlockCypherWalletService $blockCypherWalletService,
        BlockCypherAddressService $blockCypherAddressService
    )
    {
        $this->walletService = $walletService;
        $this->blockCypherWalletService = $blockCypherWalletService;
        $this->blockCypherAddressService = $blockCypherAddressService;
        $this->apiRouter = new ApiRouter();
        $this->explorerRouter = new ExplorerRouter();
    }

    /**
     * @param string $walletId
     * @return AddressListItemDto[]
     */
    public function listWalletAddresses($walletId)
    {
        $wallet = $this->walletService->getWallet(new WalletId($walletId));
        $addresses = $this->walletService->listWalletAddresses(new WalletId($walletId));

        if (!$addresses) {
            return array();
        }

        $blockCypherAddressBalances = $this->getBlockCypherWalletAddressBalances($wallet);

        $apiRouter = new ApiRouter();
        $explorerRouter = new ExplorerRouter();

        $addressListItemDtos = AddressListItemDtoArray::From(
            $wallet,
            $addresses,
            $blockCypherAddressBalances,
            $apiRouter,
            $explorerRouter
        );

        return $addressListItemDtos;
    }

    /**
     * @param Wallet $wallet
     * @return BlockCypherAddressBalance[]
     * @throws \Exception
     */
    private function getBlockCypherWalletAddressBalances(Wallet $wallet)
    {
        $blockCypherWallet = $this->blockCypherWalletService->getWallet(
            $wallet->getId()->getValue(),
            $wallet->getCoinSymbol(),
            $wallet->getToken()
        );

        $addressList = $blockCypherWallet->getAddresses();

        $blockCypherAddressBalances = $this->blockCypherAddressService->getMultipleAddressBalance(
            $addressList,
            $wallet->getCoinSymbol(),
            $wallet->getToken()
        );

        $blockCypherAddressBalancesArray = array();
        foreach ($blockCypherAddressBalances as $blockCypherAddressBalance) {
            $blockCypherAddressBalancesArray[$blockCypherAddressBalance->getAddress()] = $blockCypherAddressBalance;
        }

        return $blockCypherAddressBalancesArray;
    }

    /**
     * @return WalletListItemDto[]
     */
    public function listWallets()
    {
        $wallets = $this->walletService->listWallets();

        $walletBalances = $this->getMultipleWalletBalances($wallets);

        $walletListItemDtos = WalletListItemDtoArray::from($wallets, $walletBalances, $this->apiRouter);

        return $walletListItemDtos;
    }

    /**
     * @param Wallet[] $wallets
     * @return BigMoney[]
     */
    private function getMultipleWalletBalances($wallets)
    {
        $walletBalances = array();
        foreach ($wallets as $wallet) {
            $balance = $this->getWalletBalance($wallet);
            $walletBalances[$wallet->getId()->getValue()] = $balance;
        }
        return $walletBalances;
    }

    /**
     * @param Wallet $wallet
     * @return BigMoney|null
     */
    private function getWalletBalance(Wallet $wallet)
    {
        $balance = $this->blockCypherWalletService->getWalletFinalBalance(
            $wallet->getId()->getValue(),
            $wallet->getCoinSymbol(),
            $wallet->getToken()
        );
        return $balance;
    }

    /**
     * @param string $userId
     * @return WalletListItemDto[]
     */
    public function listWalletsOfUserId($userId)
    {
        $wallets = $this->walletService->listWalletsOfUserId(new UserId($userId));

        $walletBalances = $this->getMultipleWalletBalances($wallets);

        $walletListItemDtos = WalletListItemDtoArray::from($wallets, $walletBalances, $this->apiRouter);

        return $walletListItemDtos;
    }

    /**
     * @param string $walletId
     * @return TransactionListDto
     */
    public function listWalletTransactions($walletId)
    {
        $wallet = $this->walletService->getWallet(new WalletId($walletId));
        $transactions = $this->walletService->listWalletTransactions(new WalletId($walletId));

        $blockCypherAddress = $this->getBlockCypherAddress($wallet);

        $transactionListDto = TransactionListDto::from(
            $wallet,
            $transactions,
            $blockCypherAddress,
            $this->apiRouter,
            $this->explorerRouter
        );

        return $transactionListDto;
    }

    /**
     * @param Wallet $wallet
     * @return BlockCypherAddress
     */
    private function getBlockCypherAddress(Wallet $wallet)
    {
        $blockCypherAddress = null;

        $blockCypherAddress = $this->blockCypherAddressService->getAddress(
            $wallet->getId()->getValue(),
            $wallet->getCoinSymbol(),
            $wallet->getToken()
        );

        return $blockCypherAddress;
    }

    /**
     * @param string $walletId
     * @return WalletDto|null
     */
    public function getWallet($walletId)
    {
        $wallet = $this->walletService->getWallet(new WalletId($walletId));

        $blockCypherAddress = null;

        $blockCypherAddress = $this->blockCypherAddressService->getAddress(
            $wallet->getId()->getValue(),
            $wallet->getCoinSymbol(),
            $wallet->getToken()
        );

        if ($blockCypherAddress === null) {
            return null;
        }

        return WalletDto::from($wallet, $blockCypherAddress);
    }
}