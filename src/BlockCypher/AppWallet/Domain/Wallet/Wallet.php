<?php

namespace BlockCypher\AppWallet\Domain\Wallet;

use BlockCypher\Api\Wallet as ExternalWallet;
use BlockCypher\AppCommon\App\Service\Clock;
use BlockCypher\AppCommon\App\Service\Encryptor;
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
     * @var string WalletService token
     */
    private $token;

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

        // TODO: add to constructor. User could add token when new Wallet is created
        // or it can be retrieved from user profile. Second option makes more sense as
        // a user can have more than one wallet with the same token.
        /** @noinspection SpellCheckingInspection */
        $BLOCKCYPHER_PUBLIC_KEY = 'c0afcccdde5081d6429de37d16166ead';
        $this->token = $BLOCKCYPHER_PUBLIC_KEY;

        //$this->syncAddressesFromWalletService();
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
     * @param Encryptor $encryptor
     * @return EncryptedWallet
     */
    public function encryptUsing(Encryptor $encryptor)
    {
        $encryptedWallet = new EncryptedWallet(
            $this->id,
            $this->accountId,
            $this->coin,
            $this->creationTime,
            $this->encryptAddressesUsing($encryptor),
            $this->walletService
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
        $walletName = $this->getWalletName();

        $this->createExternalWalletIfNotExist($walletName, $this->token);

        $walletGenerateAddressResponse = $this->walletService->generateAddress(
            $walletName,
            $this->coin,
            $this->token
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
     * @return string
     */
    private function getWalletName()
    {
        $walletName = $this->id->getValue();
        return $walletName;
    }

    /**
     * @param $walletName
     * @param $token
     */
    private function createExternalWalletIfNotExist($walletName, $token)
    {
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
    private function containsAddress($address)
    {
        if (is_string($address)) {
            return $this->containsAddressString($address);
        } else {
            return $this->containsAddressObject($address);
        }
    }

    /**
     * @param Address[] $addresses
     * @return $this
     */
//    private function setAddresses($addresses)
//    {
//        $this->addresses = $addresses;
//        return $this;
//    }

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
     * Get addresses from external wallet and add them to the current wallet.
     * @throws \Exception
     */
    public function syncAddressesFromWalletService()
    {
        $externalWallet = $this->walletService->getWallet(
            $this->getWalletName(),
            $this->coin,
            $this->token
        );

        if ($externalWallet === null) {
            // TODO: custom domain exception
            throw new \Exception(sprintf("Wallet not found in external service"));
        }

        $cont = 0;
        foreach ($externalWallet->getAddresses() as $externalAddress) {
            if (!$this->containsAddress($externalAddress)) {
                $tag = "Imported Address from API #$cont";
                $address = new Address(
                    $externalAddress,
                    $this->id,
                    $this->creationTime,
                    $tag,
                    '',
                    $externalAddress,
                    '',
                    ''
                );

                $this->addAddress($address);
            }

            $cont++;
        }
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
     * @return WalletService
     */
    public function getWalletService()
    {
        return $this->walletService;
    }
}