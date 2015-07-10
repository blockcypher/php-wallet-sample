<?php

namespace BlockCypher\AppWallet\Domain\Address;

use BlockCypher\AppCommon\App\Service\Decryptor;
use BlockCypher\AppCommon\Domain\Decryptable;
use BlockCypher\AppCommon\Domain\Model;
use BlockCypher\AppWallet\Domain\Wallet\WalletId;

/**
 * Class Address
 * @package BlockCypher\AppWallet\Domain\Address
 */
class EncryptedAddress extends Model implements Decryptable
{
    /**
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
     * @param $address
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
     * @param EncryptedAddress[] $encryptedAddresses
     * @return array
     */
    public static function ObjectArrayToArray($encryptedAddresses)
    {
        $result = array();
        foreach ($encryptedAddresses as $encryptedAddress) {
            $result[] = $encryptedAddress->toArray();
        }
        return $result;
    }

//    /**
//     * Return an array with all addresses (only bitcoin address)
//     * @param Address[] $addresses
//     * @return array
//     */
//    public static function ObjectArrayToAddressList($addresses)
//    {
//        $addressesList = array();
//
//        if (count($addresses) == 0) {
//            return $addressesList;
//        }
//
//        foreach ($addresses as $address) {
//            $addressesList[] = $address->getAddress();
//        }
//
//        return $addressesList;
//    }

    /**
     * @return array
     */
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
     * @param array $encryptedAddresses
     * @return EncryptedAddress[]
     */
    public static function ArrayToObjectArray($encryptedAddresses)
    {
        $result = array();
        foreach ($encryptedAddresses as $encryptedAddress) {
            $result[] = self::FromArray($encryptedAddress);
        }
        return $result;
    }

    /**
     * @param array $entityAsArray
     * @return $this
     */
    public static function fromArray($entityAsArray)
    {
        $encryptedAddress = new self(
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

        return $encryptedAddress;
    }

    /**
     * @param Decryptor $decryptor
     * @return Address
     */
    public function decryptUsing(Decryptor $decryptor)
    {
        $address = new Address(
            $this->id,
            $this->walletId,
            $this->address,
            $this->tag,
            $decryptor->decrypt($this->private),
            $this->public,
            $decryptor->decrypt($this->wif),
            $this->callbackUrl,
            $this->creationTime
        );

        return $address;
    }

    /**
     * @return AddressId
     */
    public function getId()
    {
        return $this->id;
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
    public function getAddress()
    {
        return $this->address;
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
}