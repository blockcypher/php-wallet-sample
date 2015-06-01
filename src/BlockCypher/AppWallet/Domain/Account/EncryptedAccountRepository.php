<?php

namespace BlockCypher\AppWallet\Domain\Account;

interface EncryptedAccountRepository
{
    /**
     * @return AccountId
     * @throws \Exception
     */
    public function nextIdentity();

    /**
     * @param AccountId $accountId
     * @return EncryptedAccount
     */
    public function accountOfId(AccountId $accountId);

    /**
     * @param EncryptedAccount $account
     */
    public function insert(EncryptedAccount $account);

    /**
     * @param EncryptedAccount[] $accounts
     * @throws \Exception
     */
    public function insertAll($accounts);

    /**
     * @param EncryptedAccount $account
     * @throws \Exception
     */
    public function update(EncryptedAccount $account);

    /**
     * @param EncryptedAccount[] $accounts
     * @throws \Exception
     */
    public function updateAll($accounts);

    /**
     * @param EncryptedAccount $account
     * @throws \Exception
     */
    public function delete(EncryptedAccount $account);

    /**
     * @param EncryptedAccount[] $accounts
     * @throws \Exception
     */
    public function deleteAll($accounts);

    /**
     * @param EncryptedAccountSpecification $specification
     * @return EncryptedAccount[]
     * @throws \Exception
     */
    public function query($specification);

    /**
     * @return EncryptedAccount[]
     */
    public function findAll();
}