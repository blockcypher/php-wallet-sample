<?php

namespace BlockCypher\AppWallet\Domain\Account;

use BlockCypher\AppCommon\Domain\BigMoney;
use Money\Currency;

interface AccountInterface
{
    /**
     * Get id
     *
     * @return AccountId
     */
    public function id();

    /**
     * Get account type. AccountType enum
     *
     * @return string
     */
    public function type();

    /**
     * Account creation time
     *
     * @return \DateTime
     */
    public function creationTime();

    /**
     * Account tag
     * @return string
     */
    public function tag();

    /**
     * @return BigMoney
     * @throws \Exception
     */
    public function balance();

    /**
     * @return Currency
     * @throws \Exception
     */
    public function currency();

    /**
     * @param string $newTag
     */
    public function changeTag($newTag);

    /**
     * TODO: is this only a repository method?
     */
    public function delete();

    /**
     * Set this account as primary
     * TODO: should be a user method: $user->setPrimaryAccount($account) ?
     * @param $user
     */
    public function setAsPrimary($user);

    /**
     * @param BigMoney $amount
     * @param \DateTime $date
     * @return
     */
    public function deposit(BigMoney $amount, \DateTime $date);

    /**
     * @param BigMoney $amount
     * @param \DateTime $date
     * @return mixed
     */
    public function withdrawal(BigMoney $amount, \DateTime $date);

    /**
     * Transfer funds from this account to another account of the same currency.
     * @param AccountInterface $account
     * @param BigMoney $amount
     */
    public function transferFundsTo(AccountInterface $account, BigMoney $amount);
}