<?php

namespace BlockCypher\AppWallet\Presentation\Facade;

use BlockCypher\AppCommon\App\Service\Internal\BlockCypherWalletService;
use BlockCypher\AppWallet\App\Service\WalletService;
use BlockCypher\AppWallet\Domain\Wallet\WalletId;
use BlockCypher\AppWallet\Presentation\Facade\Dto\AddressListItemDto;
use BlockCypher\AppWallet\Presentation\Facade\Dto\WalletListItemDto;

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
     */
    function __construct(
        WalletService $walletService,
        BlockCypherWalletService $blockCypherWalletService
    )
    {
        $this->walletService = $walletService;
        $this->blockCypherWalletService = $blockCypherWalletService;
    }

    /**
     * @param string $walletId
     * @return string[]
     */
    public function listWalletAddresses($walletId)
    {
        $addresses = $this->walletService->listWalletAddresses(new WalletId($walletId));

        $addressList = array();
        foreach ($addresses as $address) {
            $addressListItemDto = new AddressListItemDto();
            $addressListItemDto->setAddress($address->getAddress());
            $addressListItemDto->setTag($address->getTag());
            $addressListItemDto->setCreationTime($address->getCreationTime());

            $addressList[] = $addressListItemDto;
        }

        return $addressList;
    }

    /**
     * @return array
     */
    public function listWallets()
    {
        $wallets = $this->walletService->listWallets();

        //DEBUG
        //var_dump($wallets);
        //die();

        $walletList = array();
        foreach ($wallets as $wallet) {

            $walletListItemDto = new WalletListItemDto();
            $walletListItemDto->setId($wallet->getId()->getValue());
            $walletListItemDto->setCoinSymbol($wallet->getCoinSymbol());
            $walletListItemDto->setCreationTime($wallet->getCreationTime());
            $walletListItemDto->setName($wallet->getName());

            $balance = null;
            try {
                $balance = $this->blockCypherWalletService->getWalletBalance(
                    $wallet->getId()->getValue(),
                    $wallet->getCoinSymbol(),
                    $wallet->getToken()
                );
            } catch (\Exception $e) {
                // Unable to get balance from BlockCypher
                // TODO: wallet could have been deleted without using this app
            }

            if ($balance !== null) {
                $walletListItemDto->setBalance((float)(string)$balance->getAmount());
            } else {
                $walletListItemDto->setBalance(-1);
            }

            $walletList[] = $walletListItemDto;
        }

        //DEBUG
        //var_dump($walletDtos);
        //die();

        return $walletList;
    }
}