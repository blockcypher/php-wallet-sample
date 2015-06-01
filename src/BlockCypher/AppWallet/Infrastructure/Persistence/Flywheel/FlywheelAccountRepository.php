<?php

namespace BlockCypher\AppWallet\Infrastructure\Persistence\Flywheel;

use BlockCypher\AppCommon\App\Service\Decryptor;
use BlockCypher\AppCommon\App\Service\Encryptor;
use BlockCypher\AppWallet\Domain\Account\Account;
use BlockCypher\AppWallet\Domain\Account\AccountId;
use BlockCypher\AppWallet\Domain\Account\AccountRepository;
use BlockCypher\AppWallet\Domain\Account\AccountSpecification;
use BlockCypher\AppWallet\Domain\Account\EncryptedAccount;
use BlockCypher\AppWallet\Domain\Account\EncryptedAccountRepository;
use Rhumsaa\Uuid\Uuid;

class FlywheelAccountRepository implements AccountRepository
{
    /**
     * @var EncryptedAccountRepository
     */
    private $encryptedAccountRepository;

    /**
     * @var Encryptor
     */
    private $encryptor;

    /**
     * @var Decryptor
     */
    private $decryptor;

    /**
     * Constructor
     * @param EncryptedAccountRepository $encryptedAccountRepository
     * @param Encryptor $encryptor
     * @param Decryptor $decryptor
     */
    public function __construct(
        EncryptedAccountRepository $encryptedAccountRepository,
        Encryptor $encryptor,
        Decryptor $decryptor
    )
    {
        $this->encryptedAccountRepository = $encryptedAccountRepository;
        $this->encryptor = $encryptor;
        $this->decryptor = $decryptor;
    }

    /**
     * @return AccountId
     * @throws \Exception
     */
    public function nextIdentity()
    {
        return AccountId::create(
            strtoupper(Uuid::uuid4())
        );
    }

    /**
     * @param AccountId $accountId
     * @return Account
     */
    public function accountOfId(AccountId $accountId)
    {
        $account = $this->encryptedAccountRepository->accountOfId($accountId)->decryptUsing($this->decryptor);
        return $account;
    }

    /**
     * @param Account $account
     */
    public function insert(Account $account)
    {
        $this->encryptedAccountRepository->insert($account->encryptUsing($this->encryptor));
    }

    /**
     * @param Account[] $accounts
     * @throws \Exception
     */
    public function insertAll($accounts)
    {
        $this->encryptedAccountRepository->insertAll($this->encryptAccountArray($accounts));
    }

    /**
     * @param Account[] $accounts
     * @return array
     */
    private function encryptAccountArray($accounts)
    {
        if ($accounts === null)
            return null;

        $encryptedAccounts = array();
        foreach ($accounts as $account) {
            $encryptedAccounts[] = $account->encryptUsing($this->encryptor);
        }
        return $encryptedAccounts;
    }

    /**
     * @param Account $account
     * @throws \Exception
     */
    public function update(Account $account)
    {
        $this->encryptedAccountRepository->update($account->encryptUsing($this->encryptor));
    }

    /**
     * @param Account[] $accounts
     * @throws \Exception
     */
    public function updateAll($accounts)
    {
        $this->encryptedAccountRepository->updateAll($this->encryptAccountArray($accounts));
    }

    /**
     * @param Account $account
     * @throws \Exception
     */
    public function delete(Account $account)
    {
        $this->encryptedAccountRepository->delete($account->encryptUsing($this->encryptor));
    }

    /**
     * @param Account[] $accounts
     * @throws \Exception
     */
    public function deleteAll($accounts)
    {
        $this->encryptedAccountRepository->deleteAll($this->encryptAccountArray($accounts));
    }

    /**
     * @param AccountSpecification $specification
     * @return Account[]
     * @throws \Exception
     */
    public function query($specification)
    {
        // TODO: Implement query() method.
        throw new \Exception('Not implemented');
    }

    /**
     * @return Account[]
     */
    public function findAll()
    {
        $encryptedAccounts = $this->encryptedAccountRepository->findAll();

        $accounts = $this->decryptEncryptedAccountArray($encryptedAccounts);

        return $accounts;
    }

    /**
     * @param EncryptedAccount[] $encryptedAccounts
     * @return Account[]
     */
    private function decryptEncryptedAccountArray($encryptedAccounts)
    {
        if ($encryptedAccounts === null)
            return null;

        $accounts = array();
        foreach ($encryptedAccounts as $encryptedAccount) {
            $accounts[] = $encryptedAccount->decryptUsing($this->decryptor);
        }
        return $accounts;
    }
}