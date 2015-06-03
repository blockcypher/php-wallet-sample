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
use BlockCypher\AppWallet\Domain\Wallet\WalletRepository;
use Rhumsaa\Uuid\Uuid;

class FlywheelAccountRepository implements AccountRepository
{
    /**
     * @var EncryptedAccountRepository
     */
    private $encryptedAccountRepository;

    /**
     * @var WalletRepository
     */
    private $walletRepository;

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
     * @param WalletRepository $walletRepository
     * @param Encryptor $encryptor
     * @param Decryptor $decryptor
     */
    public function __construct(
        EncryptedAccountRepository $encryptedAccountRepository,
        WalletRepository $walletRepository,
        Encryptor $encryptor,
        Decryptor $decryptor
    )
    {
        $this->encryptedAccountRepository = $encryptedAccountRepository;
        $this->walletRepository = $walletRepository;
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
        $this->encryptedAccountRepository->insert($this->encryptAccount($account));
    }

    /**
     * @param Account $account
     * @return EncryptedAccount
     */
    private function encryptAccount(Account $account)
    {
        return $account->encryptUsing($this->encryptor);
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
            $encryptedAccounts[] = $this->encryptAccount($account);
        }
        return $encryptedAccounts;
    }

    /**
     * @param Account $account
     * @throws \Exception
     */
    public function update(Account $account)
    {
        $this->encryptedAccountRepository->update($this->encryptAccount($account));
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
        $this->encryptedAccountRepository->delete($this->encryptAccount($account));
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
            $accounts[] = $this->decryptAccount($encryptedAccount);
        }
        return $accounts;
    }

    /**
     * @param EncryptedAccount $encryptedAccount
     * @return Account
     */
    private function decryptAccount(EncryptedAccount $encryptedAccount)
    {
        // DEBUG
        //var_dump($encryptedAccount);
        //die();

        $account = $encryptedAccount->decryptUsing($this->decryptor);

        $accountId = $account->id();
        $walletRepository = $this->walletRepository;

        // DEBUG
        //var_dump($accountId);
        //die();

        // Lazy Loading with Closures
        // http://verraes.net/2011/05/lazy-loading-with-closures/
        /** @noinspection PhpUnusedParameterInspection */
        $walletReference = function ($account) use ($accountId, $walletRepository) {
            /** @var WalletRepository $walletRepository */
            $wallet = $walletRepository->walletOfAccountId($accountId);
            return $wallet;
        };
        $account->setWalletReference($walletReference);

        return $account;
    }
}