<?php

namespace BlockCypher\AppWallet\Domain\Wallet;

use BlockCypher\AppCommon\App\Service\Encryptor;
use BlockCypher\AppCommon\Domain\ArrayConversion;
use BlockCypher\AppCommon\Domain\Encryptable;
use BlockCypher\AppCommon\Domain\Model;
use BlockCypher\AppCommon\Domain\User\UserId;

/**
 * Class Wallet
 * Cryptocurrency wallet.
 * @package BlockCypher\AppWallet\Domain\Wallet
 */
class Wallet extends Model implements ArrayConversion, Encryptable
{
    /**
     * @var WalletId
     */
    private $id;

    /**
     * @var UserId
     */
    private $userId;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string WalletCoinSymbol enum
     */
    private $coinSymbol;

    /**
     * @var string token
     */
    private $token;

    /**
     * Entity creation time.
     *
     * @var \DateTime
     */
    private $creationTime;

    /**
     * Constructor
     *
     * @param WalletId $walletId
     * @param UserId $userId
     * @param string $name
     * @param string $coinSymbol
     * @param $token
     * @param \DateTime $creationTime
     * @throws \Exception
     */
    function __construct(
        WalletId $walletId,
        UserId $userId,
        $name,
        $coinSymbol,
        $token,
        \DateTime $creationTime
    )
    {
        WalletCoinSymbol::validate($coinSymbol, 'WalletCoinSymbol');

        $this->id = $walletId;
        $this->userId = $userId;
        $this->name = $name;
        $this->coinSymbol = $coinSymbol;
        $this->token = $token;
        $this->creationTime = clone $creationTime;
    }

    /**
     * @param array $entityAsArray
     * @return $this
     */
    public static function fromArray($entityAsArray)
    {
        $wallet = new self(
            WalletId::fromArray($entityAsArray['id']),
            UserId::fromArray($entityAsArray['userId']),
            $entityAsArray['name'],
            $entityAsArray['coinSymbol'],
            $entityAsArray['token'],
            $entityAsArray['creationTime']
        );

        return $wallet;
    }

    /**
     * Wallet->WalletDto
     *
     * @return array
     */
    public function toDto()
    {
        // Using arrays as DTOs
        return $this->toArray();
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $entityAsArray = array();
        $entityAsArray['id'] = $this->id->toArray();
        $entityAsArray['userId'] = $this->userId->toArray();
        $entityAsArray['name'] = $this->name;
        $entityAsArray['coinSymbol'] = $this->coinSymbol;
        $entityAsArray['token'] = $this->token;
        $entityAsArray['creationTime'] = clone $this->creationTime;

        return $entityAsArray;
    }

    /**
     * @param Encryptor $encryptor
     * @return EncryptedWallet
     */
    public function encryptUsing(Encryptor $encryptor)
    {
        $encryptedWallet = new EncryptedWallet(
            $this->id,
            $this->userId,
            $this->name,
            $this->coinSymbol,
            $this->token,
            $this->creationTime
        );

        return $encryptedWallet;
    }

    /**
     * @return WalletId
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return UserId
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getCoinSymbol()
    {
        return $this->coinSymbol;
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @return \DateTime
     */
    public function getCreationTime()
    {
        return $this->creationTime;
    }
}