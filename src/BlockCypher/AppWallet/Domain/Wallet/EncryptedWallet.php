<?php

namespace BlockCypher\AppWallet\Domain\Wallet;

use BlockCypher\AppCommon\App\Service\Decryptor;
use BlockCypher\AppCommon\Domain\ArrayConversion;
use BlockCypher\AppCommon\Domain\Model;
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
     * @var EncryptedAddress[]
     */
    private $addresses = array();

    /**
     * Constructor
     *
     * @param WalletId $walletId
     * @param string $name
     * @param string $coin
     * @param $token
     * @param \DateTime $creationTime
     * @param Address[] $addresses
     */
    function __construct(
        WalletId $walletId,
        $name,
        $coin,
        $token,
        \DateTime $creationTime,
        $addresses
    )
    {
        WalletCoinSymbol::validate($coin, 'WalletCoinSymbol');

        $this->id = $walletId;
        $this->name = $name;
        $this->coinSymbol = $coin;
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
     * @param Decryptor $decryptor
     * @return Wallet
     */
    public function decryptUsing(Decryptor $decryptor)
    {
        $wallet = new Wallet(
            $this->id,
            $this->name,
            $this->coinSymbol,
            $this->token,
            $this->creationTime,
            $this->decryptAddressesUsing($decryptor)
        );

        return $wallet;
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
}