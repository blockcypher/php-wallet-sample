<?php

namespace BlockCypher\AppWallet\Domain\Wallet;

use BlockCypher\AppWallet\Domain\Account\AccountId;

/**
 * Interface FiatWalletRepository
 * @package BlockCypher\AppWallet\Domain\Wallet
 */
interface FiatWalletRepository
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
     * @param AccountId $accountId
     * @return Wallet
     */
    public function walletOfAccountId(AccountId $accountId);

    /**
     * @param FiatWallet $fiatWallet
     */
    public function insert(FiatWallet $fiatWallet);

    /**
     * @param Wallet[] $wallets
     */
    public function insertAll($wallets);

    /**
     * @param FiatWallet $fiatWallet
     * @throws \Exception
     */
    public function update(FiatWallet $fiatWallet);

    /**
     * @param Wallet[] $wallets
     */
    public function updateAll($wallets);

    /**
     * @param FiatWallet $fiatWallet
     */
    public function delete(FiatWallet $fiatWallet);

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