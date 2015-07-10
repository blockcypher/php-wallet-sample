<?php

namespace BlockCypher\AppWallet\Domain\Transaction;

use BlockCypher\AppCommon\App\Service\Encryptor;
use BlockCypher\AppCommon\Domain\Encryptable;
use BlockCypher\AppCommon\Domain\Model;
use BlockCypher\AppWallet\Domain\Wallet\WalletId;

/**
 * Class Transaction
 * @package BlockCypher\AppWallet\Domain\Transaction
 */
class Transaction extends Model implements Encryptable
{
    /**
     * @var TransactionId
     */
    private $id;

    /**
     * @var WalletId
     */
    private $walletId;

    /**
     * @var string
     */
    private $hash;

    /**
     * @var string
     */
    private $payToAddress;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $amount;

    /**
     * Entity creation time.
     *
     * @var \DateTime
     */
    private $creationTime;

    /**
     * Constructor
     *
     * @param TransactionId $id
     * @param WalletId $walletId
     * @param string $hash
     * @param string $payToAddress
     * @param $description
     * @param $amount
     * @param \DateTime $creationTime
     */
    function __construct(
        TransactionId $id,
        WalletId $walletId,
        $hash,
        $payToAddress,
        $description,
        $amount,
        \DateTime $creationTime
    )
    {
        $this->id = $id;
        $this->walletId = $walletId;
        $this->hash = $hash;
        $this->payToAddress = $payToAddress;
        $this->description = $description;
        $this->amount = $amount;
        $this->creationTime = clone $creationTime;
    }

    /**
     * @param Transaction[] $transactions
     * @return array
     */
    public static function ObjectArrayToArray($transactions)
    {
        $result = array();
        foreach ($transactions as $transaction) {
            $result[] = $transaction->toArray();
        }
        return $result;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $entityAsArray = array();
        $entityAsArray['id'] = $this->id->toArray();
        $entityAsArray['walletId'] = $this->walletId->toArray();
        $entityAsArray['hash'] = $this->hash;
        $entityAsArray['payToAddress'] = $this->payToAddress;
        $entityAsArray['description'] = $this->description;
        $entityAsArray['amount'] = $this->amount;
        $entityAsArray['creationTime'] = clone $this->creationTime;

        return $entityAsArray;
    }

    /**
     * Transaction[]->TransactionDto[]
     *
     * @param Transaction[] $transactionArray
     * @return array
     */
    public static function arrayToDtoArray($transactionArray)
    {
        $payToTransactionDtoArray = array();
        foreach ($transactionArray as $transaction) {
            $payToTransactionDtoArray[] = $transaction->toDto();
        }

        return $payToTransactionDtoArray;
    }

    /**
     * Transaction->TransactionDto
     *
     * @return array
     */
    public function toDto()
    {
        // Using arrays as DTOs
        return $this->toArray();
    }

    /**
     * @param array $transactions
     * @return Transaction[]
     */
    public static function ArrayToObjectArray($transactions)
    {
        $result = array();
        foreach ($transactions as $transaction) {
            $result[] = Transaction::FromArray($transaction);
        }
        return $result;
    }

    /**
     * @param array $entityAsArray
     * @return $this
     */
    public static function fromArray($entityAsArray)
    {
        $payToAddress = new self(
            $entityAsArray['id'],
            $entityAsArray['walletId'],
            $entityAsArray['hash'],
            $entityAsArray['payToAddress'],
            $entityAsArray['description'],
            $entityAsArray['amount'],
            $entityAsArray['creationTime']
        );

        return $payToAddress;
    }

    /**
     * @return string
     */
    public function getPayToAddress()
    {
        return $this->payToAddress;
    }

    /**
     * @param Encryptor $encryptor
     * @return EncryptedTransaction
     */
    public function encryptUsing(Encryptor $encryptor)
    {
        $encryptedAddress = new EncryptedTransaction(
            $this->id,
            $this->walletId,
            $this->hash,
            $this->payToAddress,
            $this->description,
            $this->amount,
            $this->creationTime
        );

        return $encryptedAddress;
    }

    /**
     * @return WalletId
     */
    public function getWalletId()
    {
        return $this->walletId;
    }

    /**
     * @return string
     */
    public function getHash()
    {
        return $this->hash;
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

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return int
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param Transaction $transaction
     * @return bool
     */
    public function equals(Transaction $transaction)
    {
        if ($this->id->equals($transaction->getId()))
            return true;
        else
            return false;
    }

    /**
     * @return TransactionId
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $hash
     */
    public function assignNetworkTransactionHash($hash)
    {
        $this->hash = $hash;
    }
}