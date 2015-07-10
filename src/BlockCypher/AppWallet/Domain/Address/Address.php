<?php

namespace BlockCypher\AppWallet\Domain\Address;

use BlockCypher\AppCommon\App\Service\Encryptor;
use BlockCypher\AppCommon\Domain\Encryptable;
use BlockCypher\AppCommon\Domain\Model;
use BlockCypher\AppWallet\Domain\Wallet\WalletId;

/**
 * Class Address
 * @package BlockCypher\AppWallet\Domain\Address
 */
class Address extends Model implements Encryptable
{
    /**
     * Needed because an address could be added to two different wallets.
     *
     * @var AddressId
     */
    private $id;

    /**
     * @var WalletId
     */
    private $walletId;

    /**
     * @var string
     */
    private $address;

    /**
     * @var string
     */
    private $tag;

    /**
     * @var string
     */
    private $private;

    /**
     * @var string
     */
    private $public;

    /**
     * @var string
     */
    private $wif;

    /**
     * @var string
     */
    private $callbackUrl;

    /**
     * Entity creation time.
     *
     * @var \DateTime
     */
    private $creationTime;

    /**
     * Constructor
     *
     * @param AddressId $id
     * @param WalletId $walletId
     * @param string $address
     * @param $tag
     * @param $private
     * @param $public
     * @param $wif
     * @param $callbackUrl
     * @param \DateTime $creationTime
     */
    function __construct(
        AddressId $id,
        WalletId $walletId,
        $address,
        $tag,
        $private,
        $public,
        $wif,
        $callbackUrl,
        \DateTime $creationTime
    )
    {
        $this->id = $id;
        $this->walletId = $walletId;
        $this->address = $address;
        $this->tag = $tag;
        $this->private = $private;
        $this->public = $public;
        $this->wif = $wif;
        $this->callbackUrl = $callbackUrl;
        $this->creationTime = clone $creationTime;
    }

    /**
     * @param Address[] $addresses
     * @return array
     */
    public static function ObjectArrayToArray($addresses)
    {
        $result = array();
        foreach ($addresses as $address) {
            $result[] = $address->toArray();
        }
        return $result;
    }

    public function toArray()
    {
        $entityAsArray = array();
        $entityAsArray['id'] = $this->id->toArray();
        $entityAsArray['walletId'] = $this->walletId->toArray();
        $entityAsArray['address'] = $this->address;
        $entityAsArray['tag'] = $this->tag;
        $entityAsArray['private'] = $this->private;
        $entityAsArray['public'] = $this->public;
        $entityAsArray['wif'] = $this->wif;
        $entityAsArray['callbackUrl'] = $this->callbackUrl;
        $entityAsArray['creationTime'] = clone $this->creationTime;

        return $entityAsArray;
    }

    /**
     * Address[]->AddressDto[]
     *
     * @param Address[] $addressArray
     * @return array
     */
    public static function arrayToDtoArray($addressArray)
    {
        $addressDtoArray = array();
        foreach ($addressArray as $address) {
            $addressDtoArray[] = $address->toDto();
        }

        return $addressDtoArray;
    }

    /**
     * Address->AddressDto
     *
     * @return array
     */
    public function toDto()
    {
        // Using arrays as DTOs
        return $this->toArray();
    }

    /**
     * Return an array with all addresses (only bitcoin address)
     * @param Address[] $addresses
     * @return array
     */
    public static function ObjectArrayToAddressList($addresses)
    {
        $addressesList = array();

        if (count($addresses) == 0) {
            return $addressesList;
        }

        foreach ($addresses as $address) {
            $addressesList[] = $address->getAddress();
        }

        return $addressesList;
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param array $addresses
     * @return Address[] $addresses
     */
    public static function ArrayToObjectArray($addresses)
    {
        $result = array();
        foreach ($addresses as $address) {
            $result[] = Address::FromArray($address);
        }
        return $result;
    }

    /**
     * @param array $entityAsArray
     * @return $this
     */
    public static function fromArray($entityAsArray)
    {
        $address = new self(
            $entityAsArray['id'],
            $entityAsArray['walletId'],
            $entityAsArray['address'],
            $entityAsArray['tag'],
            $entityAsArray['private'],
            $entityAsArray['public'],
            $entityAsArray['wif'],
            $entityAsArray['callbackUrl'],
            $entityAsArray['creationTime']
        );

        return $address;
    }

    /**
     * @param Encryptor $encryptor
     * @return EncryptedAddress
     */
    public function encryptUsing(Encryptor $encryptor)
    {
        $encryptedAddress = new EncryptedAddress(
            $this->id,
            $this->walletId,
            $this->address,
            $this->tag,
            $encryptor->encrypt($this->private),
            $this->public,
            $encryptor->encrypt($this->wif),
            $this->callbackUrl,
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
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * @return string
     */
    public function getPrivate()
    {
        return $this->private;
    }

    /**
     * @return string
     */
    public function getPublic()
    {
        return $this->public;
    }

    /**
     * @return string
     */
    public function getWif()
    {
        return $this->wif;
    }

    /**
     * @return string
     */
    public function getCallbackUrl()
    {
        return $this->callbackUrl;
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
     * @param Address $address
     * @return bool
     */
    public function equals(Address $address)
    {
        if ($this->id->equals($address->getId()))
            return true;
        else
            return false;
    }

    /**
     * @return AddressId
     */
    public function getId()
    {
        return $this->id;
    }
}