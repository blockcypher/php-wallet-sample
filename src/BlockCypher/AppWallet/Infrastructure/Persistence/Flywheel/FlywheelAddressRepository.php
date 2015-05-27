<?php

namespace BlockCypher\AppWallet\Infrastructure\Persistence\Flywheel;

use BlockCypher\AppCommon\App\Service\Clock;
use BlockCypher\AppWallet\Domain\Address\Address;
use BlockCypher\AppWallet\Domain\Address\AddressRepository;
use BlockCypher\AppWallet\Domain\Address\AddressSpecification;
use JamesMoss\Flywheel\Config;
use JamesMoss\Flywheel\Document;
use JamesMoss\Flywheel\Repository;
use JamesMoss\Flywheel\Result;

class FlywheelAddressRepository implements AddressRepository
{
    /**
     * @var Clock
     */
    protected $clockService;

    /**
     * @var Repository
     */
    protected $repository;

    /**
     * Constructor
     * @param Clock $clockService
     */
    public function __construct(Clock $clockService)
    {
        $this->clockService = $clockService;
        // TODO: move to parameters in config.yml and pass to constructor
        // I think app/data is a good location
        $config = new Config(__DIR__ . DIRECTORY_SEPARATOR . 'data');
        $this->repository = new Repository('addresses', $config);
    }

    /**
     * @param string $address
     * @return Address
     */
    public function findAddress($address)
    {
        /** @var Result $result */
        $result = $this->repository->query()
            ->where('address', '>', $address)
            ->execute();

        $address = $this->documentToAddress($result->first());

        return $address;
    }

    /**
     * @param $addressDocument
     * @return Address
     */
    private function documentToAddress($addressDocument)
    {
        // Document property are stored as stdClass. Map to array.
        $id = array(
            'value' => $addressDocument->id->value
        );

        $creationTime = new \DateTime(
            $addressDocument->creationTime->date,
            new \DateTimeZone($addressDocument->creationTime->timezone)
        );

        $address = Address::fromArray(array(
            'id' => $id,
            'creationTime' => $creationTime,
        ));

        return $address;
    }

    /**
     * @param Address $address
     */
    public function insert(Address $address)
    {
        $addressDocument = $this->addressToDocument($address);
        $this->repository->store($addressDocument);
    }

    /**
     * @param Address $address
     * @return Document
     */
    private function addressToDocument(Address $address)
    {
        $addressDocument = new Document($address->toArray());

        /* DEBUG
        var_dump($address);
        var_dump($address->toArray());
        var_dump($addressDocument);
        die();
        */

        $addressDocument->setId($address->getAddress());
        return $addressDocument;
    }

    /**
     * @param Address[] $addresses
     * @throws \Exception
     */
    public function insertAll($addresses)
    {
        // TODO: Implement insertAll() method.
        throw new \Exception('Not implemented');
    }

    /**
     * @param Address $address
     * @throws \Exception
     */
    public function update(Address $address)
    {
        // TODO: Implement update() method.
        throw new \Exception('Not implemented');
    }

    /**
     * @param Address[] $addresses
     * @throws \Exception
     */
    public function updateAll($addresses)
    {
        // TODO: Implement updateAll() method.
        throw new \Exception('Not implemented');
    }

    /**
     * @param Address $address
     * @throws \Exception
     */
    public function delete(Address $address)
    {
        // TODO: Implement delete() method.
        throw new \Exception('Not implemented');
    }

    /**
     * @param Address[] $addresses
     * @throws \Exception
     */
    public function deleteAll($addresses)
    {
        // TODO: Implement deleteAll() method.
        throw new \Exception('Not implemented');
    }

    /**
     * @param AddressSpecification $specification
     * @return Address[]
     * @throws \Exception
     */
    public function query($specification)
    {
        // TODO: Implement query() method.
        throw new \Exception('Not implemented');
    }

    /**
     * @return Address[]
     */
    public function findAll()
    {
        /* DEBUG: insert a sample value
        $id = $this->nextIdentity();
        $address = new Address(
            $this->nextIdentity(),
            $this->clock->now()
        );
        $this->insert($address);
        */

        /** @var Document[] $result */
        $result = $this->repository->findAll();

        $addresses = $this->documentArrayToAddressArray($result);

        return $addresses;
    }

    /**
     * @param Document[] $result
     * @return Address[]
     */
    private function documentArrayToAddressArray($result)
    {
        $addresses = array();
        foreach ($result as $addressDocument) {
            $address = $this->documentToAddress($addressDocument);
            $addresses[] = $address;
        }
        return $addresses;
    }
}