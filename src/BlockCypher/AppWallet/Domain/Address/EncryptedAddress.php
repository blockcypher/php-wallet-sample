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
     * @var string
     */
    private $address;

    /**
     * @var WalletId
     */
    private $walletId;

    /**
     * Entity creation time.
     *
     * @var \DateTime
     */
    private $creationTime;

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
     * Constructor
     *
     * @param string $address
     * @param WalletId $walletId
     * @param \DateTime $creationTime
     * @param $tag
     * @param $private
     * @param $public
     * @param $wif
     * @param $callbackUrl
     */
    function __construct(
        $address,
        WalletId $walletId,
        \DateTime $creationTime,
        $tag,
        $private,
        $public,
        $wif,
        $callbackUrl
    )
    {
        $this->address = $address;
        $this->walletId = $walletId;
        $this->creationTime = clone $creationTime;
        $this->tag = $tag;
        $this->private = $private;
        $this->public = $public;
        $this->wif = $wif;
        $this->callbackUrl = $callbackUrl;
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
        $encryptedAddress = new self(
            $entityAsArray['address'],
            $entityAsArray['walletId'],
            $entityAsArray['creationTime'],
            $entityAsArray['tag'],
            $entityAsArray['private'],
            $entityAsArray['public'],
            $entityAsArray['wif'],
            $entityAsArray['callbackUrl']
        );

        return $encryptedAddress;
    }

    public function toArray()
    {
        $entityAsArray = array();
        $entityAsArray['address'] = $this->address;
        $entityAsArray['walletId'] = $this->walletId->toArray();
        $entityAsArray['creationTime'] = clone $this->creationTime;
        $entityAsArray['tag'] = $this->tag;
        $entityAsArray['private'] = $this->private;
        $entityAsArray['public'] = $this->public;
        $entityAsArray['wif'] = $this->wif;
        $entityAsArray['callbackUrl'] = $this->callbackUrl;

        return $entityAsArray;
    }

    /**
     * @param Decryptor $decryptor
     * @return Address
     */
    public function decryptUsing(Decryptor $decryptor)
    {
        $address = new Address(
            $this->address,
            $this->walletId,
            $this->creationTime,
            $this->tag,
            $decryptor->decrypt($this->private),
            $this->public,
            $decryptor->decrypt($this->wif),
            $this->callbackUrl
        );

        return $address;
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @return WalletId
     */
    public function getWalletId()
    {
        return $this->walletId;
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
}