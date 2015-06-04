<?php

namespace BlockCypher\AppWallet\Infrastructure\Persistence\Flywheel;

use BlockCypher\AppCommon\App\Service\Clock;
use BlockCypher\AppWallet\Domain\Account\AccountId;
use BlockCypher\AppWallet\Domain\Account\EncryptedAccount;
use BlockCypher\AppWallet\Domain\Account\EncryptedAccountRepository;
use BlockCypher\AppWallet\Domain\Account\EncryptedAccountSpecification;
use JamesMoss\Flywheel\Config;
use JamesMoss\Flywheel\Document;
use JamesMoss\Flywheel\Repository;
use JamesMoss\Flywheel\Result;
use Rhumsaa\Uuid\Uuid;

/**
 * Class FlywheelEncryptedAccountRepository
 * @package BlockCypher\AppWallet\Infrastructure\Persistence\Flywheel
 */
class FlywheelEncryptedAccountRepository implements EncryptedAccountRepository
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
     * Constructor
     * @param Clock $clockService
     * @param string $dataDir
     */
    public function __construct(Clock $clockService, $dataDir)
    {
        $this->clock = $clockService;
        $config = new Config($dataDir);
        $this->repository = new Repository('accounts', $config);
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
     * @param AccountId $encryptedAccountId
     * @return EncryptedAccount
     * @throws \Exception
     */
    public function accountOfId(AccountId $encryptedAccountId)
    {
        /** @var Result $result */
        $result = $this->repository->query()
            ->where('id', '==', $encryptedAccountId->getValue())
            ->execute();

        // DEBUG
        //var_dump($result);

        if ($result === false) {
            return null;
        }

        if ($result->count() == 0) {
            return null;
        }

        $encryptedAccount = $this->documentToAccount($result->first());

        return $encryptedAccount;
    }

    /**
     * @param $encryptedAccountDocument
     * @return EncryptedAccount
     */
    private function documentToAccount($encryptedAccountDocument)
    {
        //DEBUG
        //var_dump($encryptedAccountDocument);
        //die();

        $encryptedAccount = unserialize($encryptedAccountDocument->data);

        //DEBUG
        //var_dump($encryptedAccount);
        //die();

        return $encryptedAccount;
    }

    /**
     * @param EncryptedAccount $encryptedAccount
     */
    public function insert(EncryptedAccount $encryptedAccount)
    {
        $encryptedAccountDocument = $this->accountToDocument($encryptedAccount);
        $this->repository->store($encryptedAccountDocument);
    }

    /**
     * @param EncryptedAccount $encryptedAccount
     * @return Document
     */
    private function accountToDocument(EncryptedAccount $encryptedAccount)
    {
        $searchFields = array(
            'id' => $encryptedAccount->getId()->getValue(),
            'type' => $encryptedAccount->getType(),
            'creationTime' => $encryptedAccount->getCreationTime(),
        );

        $docArray = $searchFields;
        $docArray['data'] = serialize($encryptedAccount);

        $encryptedAccountDocument = new Document($docArray);
        $encryptedAccountDocument->setId($encryptedAccount->getId()->getValue());

        // DEBUG
        //var_dump($encryptedAccount);
        //var_dump($encryptedAccountDocument);
        //die();

        return $encryptedAccountDocument;
    }

    /**
     * @param EncryptedAccount[] $encryptedAccounts
     * @throws \Exception
     */
    public function insertAll($encryptedAccounts)
    {
        // TODO: Implement insertAll() method.
        throw new \Exception('Not implemented');
    }

    /**
     * @param EncryptedAccount $encryptedAccount
     * @throws \Exception
     */
    public function update(EncryptedAccount $encryptedAccount)
    {
        $encryptedAccountDocument = $this->accountToDocument($encryptedAccount);
        $this->repository->update($encryptedAccountDocument);
    }

    /**
     * @param EncryptedAccount[] $encryptedAccounts
     * @throws \Exception
     */
    public function updateAll($encryptedAccounts)
    {
        // TODO: Implement updateAll() method.
        throw new \Exception('Not implemented');
    }

    /**
     * @param EncryptedAccount $encryptedAccount
     * @throws \Exception
     */
    public function delete(EncryptedAccount $encryptedAccount)
    {
        // TODO: Implement delete() method.
        throw new \Exception('Not implemented');
    }

    /**
     * @param EncryptedAccount[] $encryptedAccounts
     * @throws \Exception
     */
    public function deleteAll($encryptedAccounts)
    {
        // TODO: Implement deleteAll() method.
        throw new \Exception('Not implemented');
    }

    /**
     * @param EncryptedAccountSpecification $specification
     * @return EncryptedAccount[]
     * @throws \Exception
     */
    public function query($specification)
    {
        // TODO: Implement query() method.
        throw new \Exception('Not implemented');
    }

    /**
     * @return EncryptedAccount[]
     */
    public function findAll()
    {
        // DEBUG: insert a sample value
        /*$encryptedAccount = new Account(
            $this->nextIdentity(),
            AccountType::BTC,
            $this->clock->now(),
            'Default'
        );
        $this->insert($encryptedAccount);*/

        /** @var Document[] $result */
        $result = $this->repository->findAll();

        $encryptedAccounts = $this->documentArrayToAccountArray($result);

        return $encryptedAccounts;
    }

    /**
     * @param Document[] $result
     * @return EncryptedAccount[]
     */
    private function documentArrayToAccountArray($result)
    {
        $encryptedAccounts = array();
        foreach ($result as $encryptedAccountDocument) {
            $encryptedAccount = $this->documentToAccount($encryptedAccountDocument);
            $encryptedAccounts[] = $encryptedAccount;
        }
        return $encryptedAccounts;
    }
}