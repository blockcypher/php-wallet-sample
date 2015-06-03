<?php

namespace BlockCypher\AppWallet\Domain\Wallet;

use BlockCypher\Api\Wallet as ExternalWallet;
use BlockCypher\AppCommon\App\Service\Clock;
use BlockCypher\AppCommon\App\Service\Encryptor;
use BlockCypher\AppCommon\App\Service\WalletService;
use BlockCypher\AppCommon\Domain\ArrayConversion;
use BlockCypher\AppCommon\Domain\BigMoney;
use BlockCypher\AppCommon\Domain\Encryptable;
use BlockCypher\AppCommon\Domain\Model;
use BlockCypher\AppWallet\Domain\Account\AccountId;
use BlockCypher\AppWallet\Domain\Address\Address;
use Money\Currency;

/**
 * Class Wallet
 * Cryptocurrency wallet.
 * @package BlockCypher\AppWallet\Domain\Wallet
 */
class Wallet extends Model implements WalletInterface, ArrayConversion, Encryptable
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
     * @var string
     */
    private $walletName;


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
        $this->walletName = $this->id->getValue();

        //$this->syncAddressesFromWalletService();
    }

    /**
     * @param array $entityAsArray
     * @return Wallet
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
        $this->createExternalWalletIfNotExist($this->walletName, $this->token);

        $walletGenerateAddressResponse = $this->walletService->generateAddress(
            $this->walletName,
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

    private function createExternalWalletIfNotExist()
    {
        $externalWallet = $this->walletService->getWallet($this->walletName, $this->coin, $this->token);

        if ($externalWallet === null) {
            // Wallet has not been created in BlockCypher
            // TODO: it should be created when Wallet instance is created.
            // It should have the same life cycle than Wallet
            $externalWallet = new ExternalWallet();
            $externalWallet->setName($this->id->getValue());
            $externalWallet->setAddresses(Address::ObjectArrayToAddressList($this->getAddresses()));
            $this->walletService->createWallet($externalWallet, $this->coin, $this->token);
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
     * @param Address[] $addresses
     * @return $this
     */
//    private function setAddresses($addresses)
//    {
//        $this->addresses = $addresses;
//        return $this;
//    }

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
        $externalWalletAddresses = array();
        if (is_array($externalWallet->getAddresses())) {
            // if external wallet has no addresses getAddresses method will return null
            $externalWalletAddresses = $externalWallet->getAddresses();
        }
        foreach ($externalWalletAddresses as $externalAddress) {
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
     * @return string
     */
    private function getWalletName()
    {
        return $this->walletName;
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

    /**
     * Get id
     *
     * @return WalletId
     */
    public function id()
    {
        // TODO: Implement id() method.
    }

    /**
     * Wallet unique name.
     *
     * @return string
     */
    public function name()
    {
        // TODO: Implement name() method.
    }

    /**
     * Account which uses this wallet.
     *
     * @return AccountId
     */
    public function accountId()
    {
        // TODO: Implement accountId() method.
    }

    /**
     * Wallet creation time.
     *
     * @return \DateTime
     */
    public function creationTime()
    {
        // TODO: Implement creationTime() method.
    }

    /**
     * @return BigMoney
     * @throws \Exception
     */
    public function balance()
    {
        // TODO: check before all methods or do it only in constructor.
        // Wallets could be created and waiting for external service to activate them.
        // For example if there is no connectivity between app server and BlockCypher server
        // when the user is creating a new account-wallet
        // This way we delay wallet creation until is really needed.
        $this->createExternalWalletIfNotExist($this->walletName, $this->token);

        return $this->walletService->getWalletBalance(
            $this->walletName,
            $this->coin,
            $this->token
        );
    }

    /**
     * @return Currency
     * @throws \Exception
     */
    public function currency()
    {
        // TODO: Implement currency() method.
    }
}