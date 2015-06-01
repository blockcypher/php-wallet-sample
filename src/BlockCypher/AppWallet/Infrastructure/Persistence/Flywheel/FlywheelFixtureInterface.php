<?php

namespace BlockCypher\AppWallet\Infrastructure\Persistence\Flywheel;

/**
 * Interface FlywheelFixtureInterface
 */
interface FlywheelFixtureInterface
{
    /**
     * Create a sample wallet
     * @param $repository
     */
    public function loadFixtures($repository);
}