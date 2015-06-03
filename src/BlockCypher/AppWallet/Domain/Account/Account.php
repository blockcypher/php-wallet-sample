<?php

namespace BlockCypher\AppWallet\Domain\Account;

use BlockCypher\AppCommon\App\Service\Clock;
use BlockCypher\AppCommon\App\Service\Encryptor;
use BlockCypher\AppCommon\App\Service\WalletService;
use BlockCypher\AppCommon\Domain\ArrayConversion;
use BlockCypher\AppCommon\Domain\BigMoney;
use BlockCypher\AppCommon\Domain\Encryptable;
use BlockCypher\AppCommon\Domain\Model;
use BlockCypher\AppWallet\Domain\Wallet\FiatWallet;
use BlockCypher\AppWallet\Domain\Wallet\FiatWalletRepository;
use BlockCypher\AppWallet\Domain\Wallet\Wallet;
use BlockCypher\AppWallet\Domain\Wallet\WalletCoin;
use BlockCypher\AppWallet\Domain\Wallet\WalletInterface;
use BlockCypher\AppWallet\Domain\Wallet\WalletRepository;
use Closure;
use Money\Currency;

/**
 * Class Account
 * @package BlockCypher\AppWallet\Domain\Account
 */
class Account extends Model implements AccountInterface, ArrayConversion, Encryptable
{
    /**
     * @var AccountId
     */
    private $id;

    /**
     * @var string AccountType enum
     */
    private $type;

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
     * @var WalletInterface|ArrayConversion|Encryptable
     */
    private $wallet;

    /**
     * @var
     */
    private $walletReference;

    /**
     * Constructor
     *
     * @param AccountId $accountId
     * @param string $type
     * @param \DateTime $creationTime
     * @param $tag
     */
    function __construct(
        AccountId $accountId,
        $type,
        \DateTime $creationTime,
        $tag
    )
    {
        $this->id = $accountId;
        $this->type = $type;
        $this->creationTime = clone $creationTime;
        $this->tag = $tag;
        $this->wallet = null;  // Lazy loading. See setWalletReference
    }

    /**
     * AccountDto[]->Account[]
     *
     * @param array $accountDtoArray
     * @return Account[]
     */
    public static function arrayFrom($accountDtoArray)
    {
        $accountArray = array();
        foreach ($accountDtoArray as $accountDto) {
            $accountArray[] = Account::From($accountDto);
        }

        return $accountArray;
    }

    /**
     * AccountDto->Account
     *
     * @param $dto
     * @return Account
     */
    public static function from($dto)
    {
        // Using arrays as DTOs
        return Account::fromArray($dto);
    }

    /**
     * Array->Account
     *
     * @param array $entityAsArray
     * @return Account
     */
    public static function fromArray($entityAsArray)
    {
        //$walletClass = $entityAsArray['walletType'];
        // Call Wallet static fromArray constructor: Wallet::fromArray or FiatWallet::fromArray
        /** @var WalletInterface $wallet */
        //$wallet = call_user_func("$walletClass::fromArray", $entityAsArray['wallet']);

        $account = new self(
            AccountId::fromArray($entityAsArray['id']),
            $entityAsArray['type'],
            $entityAsArray['creationTime'],
            $entityAsArray['tag']
        );

        return $account;
    }

    /**
     * Account[]->AccountDto[]
     *
     * @param Account[] $accountArray
     * @return array
     */
    public static function arrayToDtoArray($accountArray)
    {
        $accountDtoArray = array();
        foreach ($accountArray as $account) {
            $accountDtoArray[] = $account->toDto();
        }

        return $accountDtoArray;
    }

    /**
     * Account->AccountDto
     *
     * @return array
     */
    public function toDto()
    {
        // Using arrays as DTOs
        return $this->toArray();
    }

    /**
     * Account->Array
     *
     * @return array
     */
    public function toArray()
    {
        $entityAsArray = array();
        $entityAsArray['id'] = $this->id->toArray();
        $entityAsArray['type'] = $this->type;
        $entityAsArray['creationTime'] = clone $this->creationTime;
        $entityAsArray['tag'] = $this->tag;

        // Calculated properties
        // TODO: string to float conversion can be not possible (overflow)
        $entityAsArray['balance'] = (float)(string)$this->balance()->getAmount();

        //$entityAsArray['wallet'] = $this->wallet->toArray();
        //$entityAsArray['walletType'] = get_class($this->wallet);

        return $entityAsArray;
    }

    /**
     * @return BigMoney
     * @throws \Exception
     */
    public function balance()
    {
        //DEBUG
        //var_dump($this->wallet());

        $balance = $this->wallet()->balance();

        // TODO: error if external wallet (BlockCypher) has not been created yet.
        // It should be created when the Account is created.
        if ($balance === null) {
            throw new \Exception("Wallet balance can not be obtained");
        }

        return $balance;
    }

    /**
     * Account wallet
     * @return WalletInterface
     */
    public function wallet()
    {
        if (!isset($this->wallet)) {
            $reference = $this->walletReference;
            $this->wallet = $reference($this);
        }

        //DEBUG
        //var_dump($this->wallet);

        return $this->wallet;
    }

    /**
     * @param Encryptor $encryptor
     * @return EncryptedAccount
     */
    public function encryptUsing(Encryptor $encryptor)
    {
        $encryptedAccount = new EncryptedAccount(
            $this->id,
            $this->type,
            $this->creationTime,
            $this->tag
        //$this->wallet->encryptUsing($encryptor)
        );

        return $encryptedAccount;
    }

    /**
     * @return Currency
     * @throws \Exception
     */
    public function currency()
    {
        return AccountType::currency($this->type);
    }

    /**
     * Get id
     *
     * @return AccountId
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * Get account type. AccountType enum
     *
     * @return string
     */
    public function type()
    {
        // TODO: Implement type() method.
    }

    /**
     * Account creation time
     *
     * @return \DateTime
     */
    public function creationTime()
    {
        // TODO: Implement creationTime() method.
    }

    /**
     * Account tag
     * @return string
     */
    public function tag()
    {
        // TODO: Implement tag() method.
    }

    /**
     * @param string $newTag
     */
    public function changeTag($newTag)
    {
        // TODO: Implement changeTag() method.
    }

    /**
     * TODO: is this only a repository method?
     */
    public function delete()
    {
        // TODO: Implement delete() method.
    }

    /**
     * Set this account as primary
     * TODO: should be a user method: $user->setPrimaryAccount($account) ?
     * @param $user
     */
    public function setAsPrimary($user)
    {
        // TODO: Implement setAsPrimary() method.
    }

    /**
     * @param BigMoney $amount
     * @param \DateTime $date
     */
    public function deposit(BigMoney $amount, \DateTime $date)
    {
        // TODO: Implement deposit() method.
    }

    /**
     * @param BigMoney $amount
     * @param \DateTime $date
     * @return mixed
     */
    public function withdrawal(BigMoney $amount, \DateTime $date)
    {
        // TODO: Implement withdrawal() method.
    }

    /**
     * Transfer funds from this account to another account of the same currency.
     * @param AccountInterface $account
     * @param BigMoney $amount
     */
    public function transferFundsTo(AccountInterface $account, BigMoney $amount)
    {
        // TODO: Implement transferFundsTo() method.
    }

    /**
     * Get id
     *
     * @return AccountId
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get currency
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
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
     * @param callable $walletReference
     */
    public function setWalletReference(Closure $walletReference)
    {
        $this->walletReference = $walletReference;
    }

    /**
     * Created the wallet associated to the account
     * @param WalletRepository $walletRepository
     * @param WalletService $walletService
     * @param Clock $clock
     */
    public function createCryptoWallet(
        WalletRepository $walletRepository,
        WalletService $walletService,
        Clock $clock
    )
    {
        // TODO: validate account type
        // TODO: check if wallet already exists for this account

        $addresses = array();
        $wallet = new Wallet(
            $walletRepository->nextIdentity(),
            $this->id,
            WalletCoin::BTC,
            $clock->now(),
            $addresses,
            $walletService
        );
        $walletRepository->insert($wallet);
    }

    /**
     * Created the wallet associated to the account
     * @param FiatWalletRepository $fiatWalletRepository
     * @param Clock $clock
     */
    public function createFiatWallet(FiatWalletRepository $fiatWalletRepository, Clock $clock)
    {
        // TODO: validate account type
        // TODO: check if wallet already exists for this account

        $fiatWallet = new FiatWallet(
            $fiatWalletRepository->nextIdentity(),
            $this->id,
            WalletCoin::BTC,
            $clock->now()
        );
        $fiatWalletRepository->insert($fiatWallet);
    }
}