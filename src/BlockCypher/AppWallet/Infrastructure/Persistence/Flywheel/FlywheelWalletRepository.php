<?php

namespace BlockCypher\AppWallet\Infrastructure\Persistence\Flywheel;

use BlockCypher\AppCommon\App\Service\Decryptor;
use BlockCypher\AppCommon\App\Service\Encryptor;
use BlockCypher\AppWallet\Domain\Account\Account;
use BlockCypher\AppWallet\Domain\Account\AccountId;
use BlockCypher\AppWallet\Domain\Wallet\EncryptedWallet;
use BlockCypher\AppWallet\Domain\Wallet\EncryptedWalletRepository;
use BlockCypher\AppWallet\Domain\Wallet\Wallet;
use BlockCypher\AppWallet\Domain\Wallet\WalletId;
use BlockCypher\AppWallet\Domain\Wallet\WalletRepository;
use BlockCypher\AppWallet\Domain\Wallet\WalletSpecification;

/**
 * Class FlywheelWalletRepository
 * @package BlockCypher\AppWallet\Infrastructure\Persistence\Flywheel
 */
class FlywheelWalletRepository implements WalletRepository
{
    /**
     * @var EncryptedWalletRepository
     */
    private $encryptedWalletRepository;

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
     * @param EncryptedWalletRepository $encryptedWalletRepository
     * @param Encryptor $encryptor
     * @param Decryptor $decryptor
     */
    public function __construct(
        EncryptedWalletRepository $encryptedWalletRepository,
        Encryptor $encryptor,
        Decryptor $decryptor
    )
    {
        $this->encryptedWalletRepository = $encryptedWalletRepository;
        $this->encryptor = $encryptor;
        $this->decryptor = $decryptor;
    }

    /**
     * @return WalletId
     * @throws \Exception
     */
    public function nextIdentity()
    {
        $id = strtoupper(str_replace('.', '', uniqid('', true)));
        if (strlen($id) > 25) {
            throw new \Exception("BlockCypher wallet names can not be longer than 25 characters");
        }

        return WalletId::create($id);
    }

    /**
     * @param WalletId $walletId
     * @return Account
     */
    public function walletOfId(WalletId $walletId)
    {
        $wallet = $this->encryptedWalletRepository->walletOfId($walletId)->decryptUsing($this->decryptor);
        return $wallet;
    }

    /**
     * @param AccountId $accountId
     * @return Wallet
     */
    public function walletOfAccountId(AccountId $accountId)
    {
        $encryptedWallet = $this->encryptedWalletRepository->walletOfAccountId($accountId);

        // DEBUG
        //var_dump($encryptedWallet);

        // TODO: do the same in all methods
        if ($encryptedWallet === null)
            return null;

        $wallet = $encryptedWallet->decryptUsing($this->decryptor);

        // DEBUG
        //var_dump($wallet);

        return $wallet;
    }

    /**
     * @param Wallet $wallet
     */
    public function insert(Wallet $wallet)
    {
        $this->encryptedWalletRepository->insert($wallet->encryptUsing($this->encryptor));
    }

    /**
     * @param Wallet[] $wallets
     * @throws \Exception
     */
    public function insertAll($wallets)
    {
        $this->encryptedWalletRepository->insertAll($this->encryptWalletArray($wallets));
    }

    /**
     * @param Wallet[] $wallets
     * @return array
     */
    private function encryptWalletArray($wallets)
    {
        if ($wallets === null)
            return null;

        $encryptedWallets = array();
        foreach ($wallets as $wallet) {
            $encryptedWallets[] = $wallet->encryptUsing($this->encryptor);
        }
        return $encryptedWallets;
    }

    /**
     * @param Wallet $wallet
     * @throws \Exception
     */
    public function update(Wallet $wallet)
    {
        $this->encryptedWalletRepository->update($wallet->encryptUsing($this->encryptor));
    }

    /**
     * @param Wallet[] $wallets
     * @throws \Exception
     */
    public function updateAll($wallets)
    {
        $this->encryptedWalletRepository->updateAll($this->encryptWalletArray($wallets));
    }

    /**
     * @param Wallet $wallet
     * @throws \Exception
     */
    public function delete(Wallet $wallet)
    {
        $this->encryptedWalletRepository->delete($wallet->encryptUsing($this->encryptor));
    }

    /**
     * @param Wallet[] $wallets
     * @throws \Exception
     */
    public function deleteAll($wallets)
    {
        $this->encryptedWalletRepository->deleteAll($this->encryptWalletArray($wallets));
    }

    /**
     * @param WalletSpecification $specification
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
        $encryptedWallets = $this->encryptedWalletRepository->findAll();

        $wallets = $this->decryptEncryptedWalletArray($encryptedWallets);

        return $wallets;
    }

    /**
     * @param EncryptedWallet[] $encryptedWallets
     * @return Account[]
     */
    private function decryptEncryptedWalletArray($encryptedWallets)
    {
        if ($encryptedWallets === null)
            return null;

        $wallets = array();
        foreach ($encryptedWallets as $encryptedWallet) {
            $wallets[] = $encryptedWallet->decryptUsing($this->decryptor);
        }
        return $wallets;
    }
}