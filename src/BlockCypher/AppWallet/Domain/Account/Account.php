<?php

namespace BlockCypher\AppWallet\Domain\Account;

/**
 * Class Account
 * @package BlockCypher\AppWallet\Domain\Account
 */
class Account
{
    /**
     * @var AccountId
     */
    private $id;

    /**
     * Entity creation time.
     *
     * @var \DateTime
     */
    private $creationTime;

    /**
     * Constructor
     *
     * @param AccountId $accountId
     * @param \DateTime $creationTime
     */
    function __construct(AccountId $accountId, \DateTime $creationTime)
    {
        $this->id = clone $accountId;
        $this->creationTime = clone $creationTime;
    }

    /**
     * @param array $entityAsArray
     * @return Account
     */
    public static function fromArray($entityAsArray)
    {
        $account = new self(
            AccountId::fromArray($entityAsArray['id']),
            $entityAsArray['creationTime']
        );

        return $account;
    }

    public function toArray()
    {
        $entityAsArray = array();
        $entityAsArray['id'] = $this->id->toArray();
        $entityAsArray['creationTime'] = $this->creationTime;

        return $entityAsArray;
    }

    /**
     * Get id
     *
     * @return AccountId
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get creationTime
     *
     * @return \DateTime
     */
    public function getCreationTime()
    {
        return $this->creationTime;
    }


}