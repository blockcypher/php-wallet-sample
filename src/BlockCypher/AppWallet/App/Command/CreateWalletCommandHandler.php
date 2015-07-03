<?php

namespace BlockCypher\AppWallet\App\Command;

use BlockCypher\AppCommon\App\Service\Clock;
use BlockCypher\AppCommon\App\Service\Internal\BlockCypherWalletService;
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
        $walletName = $command->getName();
        $walletCoinSymbol = $command->getCoinSymbol();

        // TODO: Code Review. command validator
        // https://github.com/SimpleBus/MessageBus/issues/19
        // https://gist.github.com/josecelano/ded0a68154376dbec7ac
        // Alternatives to command validation using service
        // $this->createWalletCommandValidator->validate($command);
        // $this->walletCommandValidator->validateCreateWallet($command);
        // Command validation should
        // http://verraes.net/2015/02/form-command-model-validation/
        // "Note that we’re not trying to inform the user of validation errors.
        // We simply throw exceptions. The assumption here is that either the form prevents malformed values,
        // or the user is trying to bypass form validation. We don’t return friendly error messages to attackers."
        // @author @mathiasverraes

        // TODO: get token from user profile/url/form?
        $token = 'c0afcccdde5081d6429de37d16166ead';

        // Create app wallet
        $wallet = new Wallet(
            $this->walletRepository->nextIdentity(),
            $walletName,
            $walletCoinSymbol,
            $token,
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