<?php

namespace BlockCypher\AppWallet\App\Command;

use BlockCypher\AppCommon\App\Service\Clock;
use BlockCypher\AppCommon\App\Service\Internal\BlockCypherWalletService;
use BlockCypher\AppCommon\Domain\User\UserId;
use BlockCypher\AppWallet\Domain\Wallet\Wallet;
use BlockCypher\AppWallet\Domain\Wallet\WalletRepository;

/**
 * Class CreateWalletCommandHandler
 * @package BlockCypher\AppWallet\App\Command
 */
class CreateWalletCommandHandler
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
     * @var Clock
     */
    private $clock;

    /**
     * Constructor
     * @param WalletRepository $walletRepository
     * @param BlockCypherWalletService $blockCypherWalletService
     * @param Clock $clock
     */
    public function __construct(
        WalletRepository $walletRepository,
        BlockCypherWalletService $blockCypherWalletService,
        Clock $clock
    )
    {
        $this->walletRepository = $walletRepository;
        $this->blockCypherWalletService = $blockCypherWalletService;
        $this->clock = $clock;
    }

    /**
     * @param CreateWalletCommand $command
     * @throws \Exception
     */
    public function handle(CreateWalletCommand $command)
    {
        // DEBUG
        //var_dump($command);

        $commandValidator = new CreateWalletCommandValidator();
        $commandValidator->validate($command);

        $walletOwnerId = $command->getWalletOwnerId();
        $walletName = $command->getName();
        $walletCoinSymbol = $command->getCoinSymbol();
        $walletToken = $command->getToken();

        // Create app wallet
        $wallet = new Wallet(
            $this->walletRepository->nextIdentity(),
            new UserId($walletOwnerId),
            $walletName,
            $walletCoinSymbol,
            $walletToken,
            $this->clock->now(),
            array()
        );
        $this->walletRepository->insert($wallet);

        // Create BlockCypher wallet
        $this->blockCypherWalletService->createWallet(
            $wallet->getId()->getValue(),
            $wallet->getCoinSymbol(),
            $wallet->getToken()
        );
    }
}