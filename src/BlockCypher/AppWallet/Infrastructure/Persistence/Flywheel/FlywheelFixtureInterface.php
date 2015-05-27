<?php

use BlockCypher\AppWallet\Infrastructure\Persistence\Flywheel\FlywheelWalletRepository;

/**
 * Interface FlywheelFixtureInterface
 */
interface FlywheelFixtureInterface
{
    /**
     * Create a sample wallet
     * @param FlywheelWalletRepository $repository
     */
    public function loadFixtures(FlywheelWalletRepository $repository);
}