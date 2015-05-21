<?php

namespace BlockCypher\AppWallet\Infrastructure\Persistence\Flywheel;

use BlockCypher\AppCommon\App\Service\ClockService;
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
     * @var ClockService
     */
    protected $clockService;

    /**
     * @var Repository
     */
    protected $repository;

    /**
     * Constructor
     * @param ClockService $clockService
     */
    public function __construct(ClockService $clockService)
    {
        $this->clockService = $clockService;
        // TODO: move to parameters in config.yml and pass to constructor
        // I think app/data is a good location
        $config = new Config(__DIR__ . DIRECTORY_SEPARATOR . 'data');
        $this->repository = new Repository('accounts', $config);
    }

    /**
     * Returns a new instance.
     *
     * @param AccountId $accountId
     * @return Account
     */
    public function create(AccountId $accountId)
    {
        $account = new Account($accountId, $this->clockService->now());
        return $account;
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
            ->where('id', '>', $accountId->getValue())
            ->execute();

        $account = $this->documentToAccount($result->first());

        return $account;
    }

    /**
     * @param $accountDocument
     * @return Account
     */
    private function documentToAccount($accountDocument)
    {
        // Document property are stored as stdClass. Map to array.
        $id = array(
            'value' => $accountDocument->id->value
        );

        $creationTime = new \DateTime(
            $accountDocument->creationTime->date,
            new \DateTimeZone($accountDocument->creationTime->timezone)
        );

        $account = Account::fromArray(array(
            'id' => $id,
            'creationTime' => $creationTime,
        ));

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
        $accountDocument = new Document($account->toArray());

        /* DEBUG
        var_dump($account);
        var_dump($account->toArray());
        var_dump($accountDocument);
        die();
        */

        $accountDocument->setId($account->getId()->getValue());
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
        /* DEBUG: insert a sample value
        $id = $this->nextIdentity();
        $account = new Account(
            $this->nextIdentity(),
            $this->clockService->now()
        );
        $this->insert($account);
        */

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