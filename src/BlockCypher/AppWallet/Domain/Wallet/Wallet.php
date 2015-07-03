<?php

namespace BlockCypher\AppWallet\Domain\Wallet;

use BlockCypher\AppCommon\App\Service\Encryptor;
use BlockCypher\AppCommon\Domain\ArrayConversion;
use BlockCypher\AppCommon\Domain\Encryptable;
use BlockCypher\AppCommon\Domain\Model;
use BlockCypher\AppWallet\Domain\Address\Address;

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
     * @var Address[]
     */
    private $addresses = array();

    /**
     * Constructor
     *
     * @param WalletId $walletId
     * @param string $name
     * @param string $coinSymbol
     * @param $token
     * @param \DateTime $creationTime
     * @param Address[] $addresses
     */
    function __construct(
        WalletId $walletId,
        $name,
        $coinSymbol,
        $token,
        \DateTime $creationTime,
        $addresses
    )
    {
        WalletCoinSymbol::validate($coinSymbol, 'WalletCoinSymbol');

        $this->id = $walletId;
        $this->name = $name;
        $this->coinSymbol = $coinSymbol;
        $this->token = $token;
        $this->creationTime = clone $creationTime;
        $this->addresses = $addresses;
    }

    /**
     * @param array $entityAsArray
     * @return $this
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
            $entityAsArray['name'],
            $entityAsArray['coinSymbol'],
            $entityAsArray['token'],
            $entityAsArray['creationTime'],
            Address::ArrayToObjectArray($addressesArr)
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
        $entityAsArray['name'] = $this->name;
        $entityAsArray['coinSymbol'] = $this->coinSymbol;
        $entityAsArray['token'] = $this->token;
        $entityAsArray['creationTime'] = clone $this->creationTime;
        $entityAsArray['addresses'] = Address::ObjectArrayToArray($this->addresses);

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
            $this->name,
            $this->coinSymbol,
            $this->token,
            $this->creationTime,
            $this->encryptAddressesUsing($encryptor)
        );

        return $encryptedWallet;
    }

    /**
     * @param Encryptor $encryptor
     * @return array
     */
    private function encryptAddressesUsing(Encryptor $encryptor)
    {
        $encryptedAddresses = array();
        foreach ($this->addresses as $address) {
            $encryptedAddresses[] = $address->encryptUsing($encryptor);
        }
        return $encryptedAddresses;
    }

    /**
     * Append Address to the list.
     *
     * @param Address $address
     */
    public function addAddress(Address $address)
    {
        if (!$this->containsAddress($address)) {
            $this->addresses[$address->getAddress()] = $address;
        }
    }

    /**
     * @param Address|string $address
     * @return bool
     */
    public function containsAddress($address)
    {
        if (is_string($address)) {
            return $this->containsAddressString($address);
        } else {
            return $this->containsAddressObject($address);
        }
    }

    /**
     * @param string $address
     * @return bool
     */
    private function containsAddressString($address)
    {
        foreach ($this->addresses as $walletAddress) {
            if ($walletAddress->getAddress() == $address) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param Address $address
     * @return bool
     */
    private function containsAddressObject(Address $address)
    {
        foreach ($this->addresses as $walletAddress) {
            if ($walletAddress->equals($address)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Remove Address from the list.
     *
     * @param Address $address
     */
    public function removeAddress(Address $address)
    {
        if ($this->containsAddress($address)) {
            unset($this->addresses[$address->getAddress()]);
        }
    }

    /**
     * @return WalletId
     */
    public function getId()
    {
        return $this->id;
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

    /**
     * @return \BlockCypher\AppWallet\Domain\Address\Address[]
     */
    public function getAddresses()
    {
        return $this->addresses;
    }
}