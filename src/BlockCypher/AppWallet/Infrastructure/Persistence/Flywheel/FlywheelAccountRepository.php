<?php

namespace BlockCypher\AppWallet\Infrastructure\Persistence\Flywheel;

use BlockCypher\AppCommon\App\Service\Clock;
use BlockCypher\AppWallet\Domain\Account\Account;
use BlockCypher\AppWallet\Domain\Account\AccountId;
use BlockCypher\AppWallet\Domain\Account\AccountRepository;
use BlockCypher\AppWallet\Domain\Account\AccountSpecification;
use JamesMoss\Flywheel\Config;
use JamesMoss\Flywheel\Document;
use JamesMoss\Flywheel\Repository;
use JamesMoss\Flywheel\Result;
use Rhumsaa\Uuid\Uuid;

class FlywheelAccountRepository implements AccountRepository
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
     */
    public function __construct(Clock $clockService)
    {
        $this->clock = $clockService;
        // TODO: move to parameters in config.yml and pass to constructor
        // I think app/data is a good location
        $config = new Config(__DIR__ . DIRECTORY_SEPARATOR . 'data');
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
     * @param AccountId $accountId
     * @return Account
     */
    public function accountOfId(AccountId $accountId)
    {
        /** @var Result $result */
        $result = $this->repository->query()
            ->where('id', '==', $accountId->getValue())
            ->execute();

        if ($result === false) {
            return null;
        }

        if ($result->total() == 0) {
            return null;
        }

        $account = $this->documentToAccount($result->first());

        return $account;
    }

    /**
     * @param $accountDocument
     * @return Account
     */
    private function documentToAccount($accountDocument)
    {
        //DEBUG
        //var_dump($accountDocument);
        //die();

        $account = unserialize($accountDocument->data);

        //DEBUG
        //var_dump($account);
        //die();

        return $account;
    }

    /**
     * @param Account $account
     */
    public function insert(Account $account)
    {
        $accountDocument = $this->accountToDocument($account);
        $this->repository->store($accountDocument);
    }

    /**
     * @param Account $account
     * @return Document
     */
    private function accountToDocument(Account $account)
    {
        $searchFields = array(
            'id' => $account->getId()->getValue(),
            'type' => $account->getType(),
            'creationTime' => $account->getCreationTime(),
        );

        $docArray = $searchFields;
        $docArray['data'] = serialize($account);

        $accountDocument = new Document($docArray);
        $accountDocument->setId($account->getId()->getValue());

        // DEBUG
        //var_dump($account);
        //var_dump($accountDocument);
        //die();

        return $accountDocument;
    }

    /**
     * @param Account[] $accounts
     * @throws \Exception
     */
    public function insertAll($accounts)
    {
        // TODO: Implement insertAll() method.
        throw new \Exception('Not implemented');
    }

    /**
     * @param Account $account
     * @throws \Exception
     */
    public function update(Account $account)
    {
        // TODO: Implement update() method.
        throw new \Exception('Not implemented');
    }

    /**
     * @param Account[] $accounts
     * @throws \Exception
     */
    public function updateAll($accounts)
    {
        // TODO: Implement updateAll() method.
        throw new \Exception('Not implemented');
    }

    /**
     * @param Account $account
     * @throws \Exception
     */
    public function delete(Account $account)
    {
        // TODO: Implement delete() method.
        throw new \Exception('Not implemented');
    }

    /**
     * @param Account[] $accounts
     * @throws \Exception
     */
    public function deleteAll($accounts)
    {
        // TODO: Implement deleteAll() method.
        throw new \Exception('Not implemented');
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
        // DEBUG: insert a sample value
        /*$account = new Account(
            $this->nextIdentity(),
            AccountType::BTC,
            $this->clock->now(),
            'Default'
        );
        $this->insert($account);*/

        /** @var Document[] $result */
        $result = $this->repository->findAll();

        $accounts = $this->documentArrayToAccountArray($result);

        return $accounts;
    }

    /**
     * @param Document[] $result
     * @return Account[]
     */
    private function documentArrayToAccountArray($result)
    {
        $accounts = array();
        foreach ($result as $accountDocument) {
            $account = $this->documentToAccount($accountDocument);
            $accounts[] = $account;
        }
        return $accounts;
    }
}