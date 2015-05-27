<?php

namespace BlockCypher\AppWallet\Infrastructure\Persistence\Flywheel;

use BlockCypher\AppCommon\App\Service\Clock;
use BlockCypher\AppCommon\App\Service\WalletService;
use BlockCypher\AppWallet\Domain\Account\AccountId;
use BlockCypher\AppWallet\Domain\Wallet\Wallet;
use BlockCypher\AppWallet\Domain\Wallet\WalletId;
use BlockCypher\AppWallet\Domain\Wallet\WalletRepository;
use BlockCypher\AppWallet\Domain\Wallet\WalletSpecification;
use JamesMoss\Flywheel\Config;
use JamesMoss\Flywheel\Document;
use JamesMoss\Flywheel\Repository;
use JamesMoss\Flywheel\Result;

class FlywheelWalletRepository implements WalletRepository
{
    /**
     * @var Clock
     */
    private $clock;

    /**
     * @var Repository
     */
    private $repository;

    /**
     * @var WalletService
     */
    private $walletService;

    /**
     * Constructor
     * @param Clock $clockService
     * @param WalletService $walletService
     */
    public function __construct(
        Clock $clockService,
        WalletService $walletService
    )
    {
        $this->clock = $clockService;
        $this->walletService = $walletService;

        // TODO: move to parameters in config.yml and pass to constructor
        // I think app/data is a good location
        $config = new Config(__DIR__ . DIRECTORY_SEPARATOR . 'data');
        $this->repository = new Repository('wallets', $config);
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
     * @return Wallet
     */
    public function walletOfId(WalletId $walletId)
    {
        /** @var Result $result */
        $result = $this->repository->query()
            ->where('id', '==', $walletId->getValue())
            ->execute();

        if ($result === false) {
            return null;
        }

        if ($result->total() == 0) {
            return null;
        }

        $wallet = $this->documentToWallet($result->first());

        return $wallet;
    }

    /**
     * @param $walletDocument
     * @return Wallet
     */
    private function documentToWallet($walletDocument)
    {
        //DEBUG
        //var_dump($walletDocument);
        //die();

        $wallet = unserialize($walletDocument->data);

        //DEBUG
        //var_dump($wallet);
        //die();

        return $wallet;
    }

    /**
     * @param AccountId $accountId
     * @return Wallet
     */
    public function walletOfAccountId(AccountId $accountId)
    {
        /** @var Result $result */
        $result = $this->repository->query()
            ->where('accountId', '==', $accountId->getValue())
            ->execute();

        if ($result === false) {
            return null;
        }

        if ($result->total() == 0) {
            return null;
        }

        $wallet = $this->documentToWallet($result->first());

        return $wallet;
    }

    /**
     * @param Wallet $wallet
     */
    public function insert(Wallet $wallet)
    {
        $walletDocument = $this->walletToDocument($wallet);
        $this->repository->store($walletDocument);
    }

    /**
     * @param Wallet $wallet
     * @return Document
     */
    private function walletToDocument(Wallet $wallet)
    {
        $searchFields = array(
            'id' => $wallet->getId()->getValue(),
            'accountId' => $wallet->getAccountId()->getValue(),
            'coin' => $wallet->getCoin(),
            'creationTime' => clone $wallet->getCreationTime(),
        );

        $docArray = $searchFields;
        $docArray['data'] = serialize($wallet);

        $walletDocument = new Document($docArray);
        $walletDocument->setId($wallet->getId()->getValue());

        // DEBUG
        //var_dump($wallet);
        //var_dump($walletDocument);
        //die();

        return $walletDocument;
    }

    /**
     * @param Wallet[] $wallets
     * @throws \Exception
     */
    public function insertAll($wallets)
    {
        // TODO: Implement insertAll() method.
        throw new \Exception('Not implemented');
    }

    /**
     * @param Wallet $wallet
     * @throws \Exception
     */
    public function update(Wallet $wallet)
    {
        $walletDocument = $this->walletToDocument($wallet);
        if (!$this->repository->update($walletDocument)) {
            // TODO: custom exception
            throw new \Exception("Error updating wallet repository");
        };

    }

    /**
     * @param Wallet[] $wallets
     * @throws \Exception
     */
    public function updateAll($wallets)
    {
        // TODO: Implement updateAll() method.
        throw new \Exception('Not implemented');
    }

    /**
     * @param Wallet $wallet
     * @throws \Exception
     */
    public function delete(Wallet $wallet)
    {
        // TODO: Implement delete() method.
        throw new \Exception('Not implemented');
    }

    /**
     * @param Wallet[] $wallets
     * @throws \Exception
     */
    public function deleteAll($wallets)
    {
        // TODO: Implement deleteAll() method.
        throw new \Exception('Not implemented');
    }

    /**
     * @param WalletSpecification $specification
     * @return Wallet[]
     * @throws \Exception
     */
    public function query($specification)
    {
        // TODO: Implement query() method.
        throw new \Exception('Not implemented');
    }

    /**
     * @return Wallet[]
     */
    public function findAll()
    {
        /** @var Document[] $result */
        $result = $this->repository->findAll();

        $wallets = $this->documentArrayToWalletArray($result);

        return $wallets;
    }

    /**
     * @param Document[] $result
     * @return Wallet[]
     */
    private function documentArrayToWalletArray($result)
    {
        $wallets = array();
        foreach ($result as $walletDocument) {
            $wallet = $this->documentToWallet($walletDocument);
            $wallets[] = $wallet;
        }
        return $wallets;
    }
}