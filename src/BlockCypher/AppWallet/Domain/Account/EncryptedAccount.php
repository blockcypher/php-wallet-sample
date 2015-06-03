<?php

namespace BlockCypher\AppWallet\Domain\Account;

use BlockCypher\AppCommon\App\Service\Decryptor;
use BlockCypher\AppCommon\Domain\ArrayConversion;
use BlockCypher\AppCommon\Domain\BigMoney;
use BlockCypher\AppCommon\Domain\Decryptable;
use BlockCypher\AppCommon\Domain\Model;
use BlockCypher\AppWallet\Domain\Wallet\EncryptedWallet;
use Money\Currency;

/**
 * Class EncryptedAccount
 * @package BlockCypher\AppWallet\Domain\Account
 */
class EncryptedAccount extends Model implements ArrayConversion, Decryptable
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
    }

    /**
     * @param array $entityAsArray
     * @return Account
     */
    public static function fromArray($entityAsArray)
    {
        //$walletClass = $entityAsArray['walletType'];
        // Call Wallet static fromArray constructor: Wallet::fromArray or FiatWallet::fromArray
        /** @var EncryptedWallet $wallet */
        //$wallet = call_user_func("$walletClass::fromArray", $entityAsArray['wallet']);

        $account = new self(
            AccountId::fromArray($entityAsArray['id']),
            $entityAsArray['type'],
            $entityAsArray['creationTime'],
            $entityAsArray['tag']
        //$wallet
        );

        return $account;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $entityAsArray = array();
        $entityAsArray['id'] = $this->id->toArray();
        $entityAsArray['type'] = $this->type;
        $entityAsArray['creationTime'] = clone $this->creationTime;
        $entityAsArray['tag'] = $this->tag;
        //$entityAsArray['wallet'] = $this->wallet->toArray();
        //$entityAsArray['walletType'] = get_class($this->wallet);

        return $entityAsArray;
    }

    /**
     * @param Decryptor $decryptor
     * @return Account
     */
    public function decryptUsing(Decryptor $decryptor)
    {
        $account = new Account(
            $this->id,
            $this->type,
            $this->creationTime,
            $this->tag
        //$this->wallet->decryptUsing($decryptor)
        );

        return $account;
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
     * @return BigMoney
     * @throws \Exception
     */
    public function balance()
    {
        // TODO: implement method balance
        //throw new \Exception("Not implemented");
        return BigMoney::fromString('0.00000000', $this->currency());
    }

    /**
     * Mapping between AccountType and Currency
     * @return Currency
     * @throws \Exception
     */
    private function currency()
    {
        switch ($this->type) {
            case AccountType::BTC:
                return new Currency('BTC');
            case AccountType::EUR:
                return new Currency('EUR');
            default:
                throw new \Exception(sprintf("Unsupported account type %s", $this->type));
        }
    }


}