<?php

namespace BlockCypher\AppWallet\Domain\Wallet;

use BlockCypher\AppCommon\Domain\User\UserId;

/**
 * Interface WalletRepository
 * @package BlockCypher\AppWallet\Domain\Wallet
 */
interface WalletRepository
{
    /**
     * @return WalletId
     */
    public function nextIdentity();

    /**
     * @param WalletId $walletId
     * @return Wallet
     */
    public function walletOfId(WalletId $walletId);

    /**
     * @param UserId $userId
     * @return Wallet[]
     */
    public function walletsOfUserId(UserId $userId);

    /**
     * @param Wallet $wallet
     */
    public function insert(Wallet $wallet);

    /**
     * @param Wallet[] $wallets
     */
    public function insertAll($wallets);

    /**
     * @param Wallet $wallet
     * @throws \Exception
     */
    public function update(Wallet $wallet);

    /**
     * @param Wallet[] $wallets
     */
    public function updateAll($wallets);

    /**
     * @param Wallet $wallet
     */
    public function delete(Wallet $wallet);

    /**
     * @param Wallet[] $wallets
     */
    public function deleteAll($wallets);

    /**
     * @param WalletSpecification $specification
     * @return Wallet[]
     */
    public function query($specification);

    /**
     * @return Wallet[]
     */
    public function findAll();
}