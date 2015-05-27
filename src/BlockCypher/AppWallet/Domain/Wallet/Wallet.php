<?php

namespace BlockCypher\AppWallet\Domain\Wallet;

use BlockCypher\Api\Wallet as ExternalWallet;
use BlockCypher\AppCommon\App\Service\Clock;
use BlockCypher\AppCommon\App\Service\WalletService;
use BlockCypher\AppCommon\Domain\Model;
use BlockCypher\AppWallet\Domain\Account\AccountId;
use BlockCypher\AppWallet\Domain\Address\Address;

/**
 * Class Wallet
 * @package BlockCypher\AppWallet\Domain\Wallet
 */
class Wallet extends Model
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
     * @var Address[]
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
     * @param $walletService
     * @return Wallet
     */
    public static function fromArray($entityAsArray, $walletService)
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
            $walletService
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

        return $entityAsArray;
    }

    /**
     * @param $tag
     * @param $callbackUrl
     * @param Clock $clock
     * @return Address
     */
    public function generateAddress(
        $tag,
        $callbackUrl,
        Clock $clock)
    {
        // TODO: default token in WalletService constructor
        // TODO: get coin symbol from wallet currency
        $BLOCKCYPHER_PUBLIC_KEY = 'c0afcccdde5081d6429de37d16166ead';
        $token = $BLOCKCYPHER_PUBLIC_KEY;

        $walletName = $this->id->getValue();

        $externalWallet = $this->walletService->getWallet($walletName, $this->coin, $token);

        if ($externalWallet === null) {
            // Wallet has not been created in BlockCypher
            // TODO: it should be created when Wallet instance is created.
            // It should have the same life cycle than Wallet
            $externalWallet = new ExternalWallet();
            $externalWallet->setName($this->id->getValue());
            $externalWallet->setAddresses(Address::ObjectArrayToAddressList($this->getAddresses()));
            $this->walletService->createWallet($externalWallet, $this->coin, $token);
        }

        $walletGenerateAddressResponse = $this->walletService->generateAddress(
            $walletName,
            $this->coin,
            $token
        );

        $address = new Address(
            $walletGenerateAddressResponse->getAddress(),
            $this->id,
            $clock->now(),
            $tag,
            $walletGenerateAddressResponse->getPrivate(),
            $walletGenerateAddressResponse->getPublic(),
            $walletGenerateAddressResponse->getWif(),
            $callbackUrl
        );

        $this->addAddress($address);

        return $address;
    }

    /**
     * @return Address[]
     */
    public function getAddresses()
    {
        return $this->addresses;
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