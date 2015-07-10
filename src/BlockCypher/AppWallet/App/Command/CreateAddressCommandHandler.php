<?php

namespace BlockCypher\AppWallet\App\Command;

use BlockCypher\AppCommon\App\Service\Clock;
use BlockCypher\AppCommon\App\Service\Internal\BlockCypherWalletService;
use BlockCypher\AppWallet\Domain\Address\Address;
use BlockCypher\AppWallet\Domain\Address\AddressRepository;
use BlockCypher\AppWallet\Domain\Wallet\WalletId;
use BlockCypher\AppWallet\Domain\Wallet\WalletRepository;

class CreateAddressCommandHandler
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
     * @var BlockCypherWalletService
     */
    private $blockCypherWalletService;

    /**
     * @var Clock
     */
    private $clock;

    /**
     * Constructor
     * @param WalletRepository $walletRepository
     * @param AddressRepository $addressRepository
     * @param BlockCypherWalletService $blockCypherWalletService
     * @param Clock $clock
     */
    public function __construct(
        WalletRepository $walletRepository,
        AddressRepository $addressRepository,
        BlockCypherWalletService $blockCypherWalletService,
        Clock $clock
    )
    {
        $this->walletRepository = $walletRepository;
        $this->addressRepository = $addressRepository;
        $this->blockCypherWalletService = $blockCypherWalletService;
        $this->clock = $clock;
    }

    /**
     * @param CreateAddressCommand $command
     * @throws \Exception
     */
    public function handle(CreateAddressCommand $command)
    {
        // DEBUG
        //var_dump($command);

        $commandValidator = new CreateAddressCommandValidator();
        $commandValidator->validate($command);

        $walletId = $command->getWalletId();
        $addressTag = $command->getTag();
        $addressCallbackUrl = $command->getCallbackUrl();

        // DEBUG: create a sample wallet
        //$wallet = $this->walletRepository->loadFixtures();

        $wallet = $this->walletRepository->walletOfId(new WalletId($walletId));

        // DEBUG
        //var_dump($wallet);
        //die();

        if ($wallet === null) {
            // TODO: create domain exception
            throw new \Exception(sprintf("Wallet not found %s", $walletId));
        }

        // 1.- Call BlockCypher API to generate new address
        $walletGenerateAddressResponse = $this->blockCypherWalletService->generateAddress(
            $wallet->getId()->getValue(),
            $wallet->getCoinSymbol(),
            $wallet->getToken()
        );

        // 2.- Create new app Address
        $address = new Address(
            $this->addressRepository->nextIdentity(),
            new WalletId($walletId),
            $walletGenerateAddressResponse->getAddress(),
            $addressTag,
            $walletGenerateAddressResponse->getPrivate(),
            $walletGenerateAddressResponse->getPublic(),
            $walletGenerateAddressResponse->getWif(),
            $addressCallbackUrl,
            $this->clock->now()
        );

        $this->addressRepository->insert($address);
    }
}