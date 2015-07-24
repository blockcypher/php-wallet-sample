<?php

namespace BlockCypher\AppWallet\Infrastructure\Persistence\Flywheel\DataFixtures;

use BlockCypher\AppCommon\App\Service\Clock;
use BlockCypher\AppCommon\Domain\User\UserId;
use BlockCypher\AppWallet\App\Service\WalletService;
use BlockCypher\AppWallet\Domain\Wallet\Wallet;
use BlockCypher\AppWallet\Domain\Wallet\WalletCoinSymbol;
use BlockCypher\AppWallet\Domain\Wallet\WalletId;
use BlockCypher\AppWallet\Infrastructure\Persistence\Flywheel\FlywheelFixtureInterface;
use BlockCypher\AppWallet\Infrastructure\Persistence\Flywheel\FlywheelWalletRepository;
use JamesMoss\Flywheel\Repository;

/**
 * Class LoadWalletData
 * @package BlockCypher\AppWallet\Infrastructure\Persistence\Flywheel\DataFixtures
 */
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
     * @param string $token
     * @return Wallet
     */
    public function loadFixtures($repository, $token)
    {
        $wallet = new Wallet(
            new WalletId("5564B09652AFA054401239"),
            new UserId(new UserId($token), $token),
            'alice',
            WalletCoinSymbol::BTC_TESTNET,
            $token,
            $this->clock->now()
        );
        $repository->insert($wallet);

        return $wallet;
    }
}