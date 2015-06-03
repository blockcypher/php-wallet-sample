<?php

namespace BlockCypher\AppWallet\App\Command;

use BlockCypher\AppCommon\App\Service\Clock;
use BlockCypher\AppWallet\Domain\Account\AccountId;
use BlockCypher\AppWallet\Domain\Wallet\WalletRepository;

class CreateAddressCommandHandler
{
    /**
     * @var WalletRepository
     */
    private $walletRepository;

    /**
     * @var Clock
     */
    private $clock;

    /**
     * Constructor
     * @param WalletRepository $walletRepository
     * @param Clock $clock
     */
    public function __construct(
        WalletRepository $walletRepository,
        Clock $clock
    )
    {
        $this->walletRepository = $walletRepository;
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

        // TODO: command validator. See CreateAddressCommandHandler::handle for possible implementation details

        $accountId = $command->getAccountId();

        // DEBUG: create a sample wallet
        //$wallet = $this->walletRepository->loadFixtures();

        $wallet = $this->walletRepository->walletOfAccountId(new AccountId($accountId));

        // DEBUG
        //var_dump($wallet);
        //die();

        if ($wallet === null) {
            // TODO: create domain exception
            throw new \Exception(sprintf("Wallet not found for account %s", $accountId));
        }

        $wallet->generateAddress(
            $command->getTag(),
            $command->getCallbackUrl(),
            $this->clock
        );

        $this->walletRepository->update($wallet);
    }
}