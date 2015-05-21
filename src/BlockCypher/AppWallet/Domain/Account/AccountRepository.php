<?php

namespace BlockCypher\AppWallet\Domain\Account;

/**
 * Interface AccountRepository
 * @package BlockCypher\AppWallet\Domain\Account
 */
interface AccountRepository
{
    /**
     * @return AccountId
     */
    public function nextIdentity();

    /**
     * @param AccountId $accountId
     * @return Account
     */
    public function accountOfId(AccountId $accountId);

    /**
     * @param Account $account
     */
    public function insert(Account $account);

    /**
     * @param Account[] $accounts
     */
    public function insertAll($accounts);

    /**
     * @param Account $account
     */
    public function update(Account $account);

    /**
     * @param Account[] $accounts
     */
    public function updateAll($accounts);

    /**
     * @param Account $account
     */
    public function delete(Account $account);

    /**
     * @param Account[] $accounts
     */
    public function deleteAll($accounts);

    /**
     * @param AccountSpecification $specification
     * @return Account[]
     */
    public function query($specification);

    /**
     * @return Account[]
     */
    public function findAll();
}