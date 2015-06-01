<?php

namespace BlockCypher\AppWallet\Domain\Wallet;

use BlockCypher\AppWallet\Domain\Account\AccountId;

/**
 * Interface EncryptedWalletRepository
 * @package BlockCypher\AppWallet\Domain\Wallet
 */
interface EncryptedWalletRepository
{
    /**
     * @return WalletId
     */
    public function nextIdentity();

    /**
     * @param WalletId $walletId
     * @return EncryptedWallet
     */
    public function walletOfId(WalletId $walletId);

    /**
     * @param AccountId $accountId
     * @return EncryptedWallet
     */
    public function walletOfAccountId(AccountId $accountId);

    /**
     * @param EncryptedWallet $wallet
     */
    public function insert(EncryptedWallet $wallet);

    /**
     * @param EncryptedWallet[] $wallets
     */
    public function insertAll($wallets);

    /**
     * @param EncryptedWallet $wallet
     * @throws \Exception
     */
    public function update(EncryptedWallet $wallet);

    /**
     * @param EncryptedWallet[] $wallets
     */
    public function updateAll($wallets);

    /**
     * @param EncryptedWallet $wallet
     */
    public function delete(EncryptedWallet $wallet);

    /**
     * @param EncryptedWallet[] $wallets
     */
    public function deleteAll($wallets);

    /**
     * @param EncryptedWalletSpecification $specification
     * @return EncryptedWallet[]
     */
    public function query($specification);

    /**
     * @return EncryptedWallet[]
     */
    public function findAll();
}