<?php

namespace BlockCypher\AppWallet\Infrastructure\Persistence\Flywheel\DataFixtures;

use BlockCypher\AppCommon\App\Service\Clock;
use BlockCypher\AppWallet\Domain\Account\Account;
use BlockCypher\AppWallet\Domain\Account\AccountId;
use BlockCypher\AppWallet\Domain\Account\AccountType;
use BlockCypher\AppWallet\Infrastructure\Persistence\Flywheel\FlywheelAccountRepository;
use BlockCypher\AppWallet\Infrastructure\Persistence\Flywheel\FlywheelFixtureInterface;
use JamesMoss\Flywheel\Repository;

class LoadAccountData implements FlywheelFixtureInterface
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
     * @param $clock
     * @param $repository
     */
    function __construct($clock, $repository)
    {
        $this->clock = $clock;
        $this->repository = $repository;
    }

    /**
     * Create a sample wallet
     * @param FlywheelAccountRepository $repository
     */
    public function loadFixtures($repository)
    {
        $account = new Account(
            new AccountId("1A311E0C-B6A6-4679-9F7B-21FDB265E135"),
            AccountType::BTC,
            $this->clock->now(),
            'Default',
            null
        );
        $repository->insert($account);
    }
}