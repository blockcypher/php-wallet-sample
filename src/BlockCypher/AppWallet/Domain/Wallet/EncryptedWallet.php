<?php

namespace BlockCypher\AppWallet\Domain\Wallet;

use BlockCypher\AppCommon\App\Service\Decryptor;
use BlockCypher\AppCommon\App\Service\WalletService;
use BlockCypher\AppCommon\Domain\ArrayConversion;
use BlockCypher\AppCommon\Domain\Model;
use BlockCypher\AppWallet\Domain\Account\AccountId;
use BlockCypher\AppWallet\Domain\Address\Address;
use BlockCypher\AppWallet\Domain\Address\EncryptedAddress;

/**
 * Class EncryptedWallet
 * @package BlockCypher\AppWallet\Domain\Wallet
 */
class EncryptedWallet extends Model implements ArrayConversion
{
    /**
     * @var WalletId
     */
    private $id;

    /**
     * @var AccountId
     */
    private $accountId;

    /**
     * @var string WalletCoin enum
     */
    private $coin;

    /**
     * Entity creation time.
     *
     * @var \DateTime
     */
    private $creationTime;

    /**
     * @var EncryptedAddress[]
     */
    private $addresses = array();

    /**
     * @var WalletService
     */
    private $walletService;

    /**
     * Constructor
     *
     * @param WalletId $walletId
     * @param AccountId $accountId
     * @param string $coin
     * @param \DateTime $creationTime
     * @param Address[] $addresses
     * @param WalletService $walletService
     */
    function __construct(
        WalletId $walletId,
        AccountId $accountId,
        $coin,
        \DateTime $creationTime,
        $addresses,
        WalletService $walletService
    )
    {
        $this->id = $walletId;
        $this->accountId = $accountId;
        $this->coin = $coin;
        $this->creationTime = clone $creationTime;
        $this->addresses = $addresses;
        $this->walletService = $walletService;
    }

    /**
     * @param array $entityAsArray
     * @return EncryptedWallet
     */
    public static function fromArray($entityAsArray)
    {
        if (is_array($entityAsArray['addresses'])) {
            $addressesArr = $entityAsArray['addresses'];
        } else {
            $addressesArr = array();
        }

        $wallet = new self(
            WalletId::fromArray($entityAsArray['id']),
            AccountId::fromArray($entityAsArray['accountId']),
            $entityAsArray['coin'],
            $entityAsArray['creationTime'],
            Address::ArrayToObjectArray($addressesArr),
            $entityAsArray['walletService']
        );

        return $wallet;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $entityAsArray = array();
        $entityAsArray['id'] = $this->id->toArray();
        $entityAsArray['accountId'] = $this->accountId->toArray();
        $entityAsArray['creationTime'] = clone $this->creationTime;
        $entityAsArray['addresses'] = Address::ObjectArrayToArray($this->addresses);
        $entityAsArray['walletService'] = $this->walletService;

        return $entityAsArray;
    }

    /**
     * @param Decryptor $decryptor
     * @return Wallet
     */
    public function decryptUsing(Decryptor $decryptor)
    {
        $account = new Wallet(
            $this->id,
            $this->accountId,
            $this->coin,
            $this->creationTime,
            $this->decryptAddressesUsing($decryptor),
            $this->walletService
        );

        return $account;
    }

    /**
     * @param Decryptor $decryptor
     * @return array
     */
    private function decryptAddressesUsing(Decryptor $decryptor)
    {
        $decryptedAddresses = array();
        foreach ($this->addresses as $encryptedAddress) {
            $decryptedAddresses[] = $encryptedAddress->decryptUsing($decryptor);
        }
        return $decryptedAddresses;
    }

    /**
     * Append Address to the list.
     *
     * @param string $address
     * @return $this
     */
    public function addAddress($address)
    {
        if (!$this->getAddresses()) {
            return $this->setAddresses(array($address));
        } else {
            return $this->setAddresses(
                array_merge($this->getAddresses(), array($address))
            );
        }
    }

    /**
     * @return Address[]
     */
    public function getAddresses()
    {
        return $this->addresses;
    }

    /**
     * @param \string[] $addresses
     * @return $this
     */
    private function setAddresses($addresses)
    {
        $this->addresses = $addresses;
        return $this;
    }

    /**
     * Remove Address from the list.
     *
     * @param string $address
     * @return $this
     */
    public function removeAddress($address)
    {
        return $this->setAddresses(
            array_diff($this->getAddresses(), array($address))
        );
    }

    /**
     * Get id
     *
     * @return WalletId
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return AccountId
     */
    public function getAccountId()
    {
        return $this->accountId;
    }

    /**
     * @return string
     */
    public function getCoin()
    {
        return $this->coin;
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
     * @return WalletService
     */
    public function getWalletService()
    {
        return $this->walletService;
    }
}