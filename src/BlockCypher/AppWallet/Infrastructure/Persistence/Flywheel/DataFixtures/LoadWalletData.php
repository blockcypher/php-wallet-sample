<?php

namespace BlockCypher\AppWallet\Infrastructure\Persistence\Flywheel\DataFixtures;

use BlockCypher\AppCommon\App\Service\Clock;
use BlockCypher\AppCommon\App\Service\WalletService;
use BlockCypher\AppWallet\Domain\Account\AccountId;
use BlockCypher\AppWallet\Domain\Wallet\Wallet;
use BlockCypher\AppWallet\Domain\Wallet\WalletCoin;
use BlockCypher\AppWallet\Domain\Wallet\WalletId;
use BlockCypher\AppWallet\Infrastructure\Persistence\Flywheel\FlywheelFixtureInterface;
use BlockCypher\AppWallet\Infrastructure\Persistence\Flywheel\FlywheelWalletRepository;
use JamesMoss\Flywheel\Repository;

class LoadWalletData implements FlywheelFixtureInterface
{
    /**
     * @var Clock
     */
    private $clock;

    /**
     * @var Repository
     */
    private $repository;

    /**
     * @var WalletService
     */
    private $walletService;

    /**
     * @param $clock
     * @param $repository
     * @param $walletService
     */
    function __construct($clock, $repository, $walletService)
    {
        $this->clock = $clock;
        $this->repository = $repository;
        $this->walletService = $walletService;
    }

    /**
     * Create a sample wallet
     * @param FlywheelWalletRepository $repository
     */
    public function loadFixtures($repository)
    {
        $addresses = array();
        $walletService = null;
        $wallet = new Wallet(
            new WalletId("5564B09652AFA054401239"),
            new AccountId("1A311E0C-B6A6-4679-9F7B-21FDB265E135"),
            WalletCoin::BTC,
            $this->clock->now(),
            $addresses,
            $this->walletService
        );
        $repository->insert($wallet);
    }
}