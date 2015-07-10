<?php

namespace BlockCypher\AppWallet\Domain\Transaction;

use BlockCypher\AppCommon\App\Service\Decryptor;
use BlockCypher\AppCommon\Domain\Decryptable;
use BlockCypher\AppCommon\Domain\Model;
use BlockCypher\AppWallet\Domain\Wallet\WalletId;

/**
 * Class EncryptedTransaction
 * @package BlockCypher\AppWallet\Domain\Transaction
 */
class EncryptedTransaction extends Model implements Decryptable
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
     * @param $payToAddress
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
     * @param EncryptedTransaction[] $encryptedTransactions
     * @return array
     */
    public static function ObjectArrayToArray($encryptedTransactions)
    {
        $result = array();
        foreach ($encryptedTransactions as $encryptedTransaction) {
            $result[] = $encryptedTransaction->toArray();
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
     * EncryptedTransaction[]->EncryptedTransactionDto[]
     *
     * @param EncryptedTransaction[] $encryptedTransactionArray
     * @return array
     */
    public static function arrayToDtoArray($encryptedTransactionArray)
    {
        $transactionDtoArray = array();
        foreach ($encryptedTransactionArray as $encryptedTransaction) {
            $transactionDtoArray[] = $encryptedTransaction->toDto();
        }

        return $transactionDtoArray;
    }

    /**
     * EncryptedTransaction->EncryptedTransactionDto
     *
     * @return array
     */
    public function toDto()
    {
        // Using arrays as DTOs
        return $this->toArray();
    }

    /**
     * @param array $encryptedTransactions
     * @return EncryptedTransaction[]
     */
    public static function ArrayToObjectArray($encryptedTransactions)
    {
        $result = array();
        foreach ($encryptedTransactions as $transaction) {
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
        $encryptedTransaction = new self(
            $entityAsArray['id'],
            $entityAsArray['walletId'],
            $entityAsArray['hash'],
            $entityAsArray['payToAddress'],
            $entityAsArray['description'],
            $entityAsArray['amount'],
            $entityAsArray['creationTime']
        );

        return $encryptedTransaction;
    }

    /**
     * @return string
     */
    public function getPayToAddress()
    {
        return $this->payToAddress;
    }

    /**
     * @param Decryptor $decryptor
     * @return Transaction
     */
    public function decryptUsing(Decryptor $decryptor)
    {
        $transaction = new Transaction(
            $this->id,
            $this->walletId,
            $this->hash,
            $this->payToAddress,
            $this->description,
            $this->amount,
            $this->creationTime
        );
        return $transaction;
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
     * Get creationTime
     *
     * @return \DateTime
     */
    public function getCreationTime()
    {
        return $this->creationTime;
    }

    /**
     * @param EncryptedTransaction $encryptedTransaction
     * @return bool
     */
    public function equals(EncryptedTransaction $encryptedTransaction)
    {
        if ($this->id->equals($encryptedTransaction->getId()))
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
}