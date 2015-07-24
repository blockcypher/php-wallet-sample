<?php

namespace BlockCypher\AppWallet\Infrastructure\Persistence\Flywheel;

use BlockCypher\AppWallet\Domain\Wallet\Wallet;

/**
 * Interface FlywheelFixtureInterface
 */
interface FlywheelFixtureInterface
{
    /**
     * Create a sample wallet
     * @param $repository
     * @param string $token
     * @return Wallet
     */
    public function loadFixtures($repository, $token);
}