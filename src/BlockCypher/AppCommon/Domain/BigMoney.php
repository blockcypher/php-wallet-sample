<?php

namespace BlockCypher\AppCommon\Domain;

use Litipk\BigNumbers\Decimal;
use Money\BigMoney as BaseBigMoney;
use Money\Currency;
use Money\InvalidArgumentException;

/**
 * Class BigMoney
 * @package BlockCypher\AppCommon\Domain
 */
class BigMoney extends BaseBigMoney
{
    // TODO: apply these changes to BaseBigMoney

    /**
     * @param string $amount
     * @param Currency $currency
     * @return Decimal
     * @throws InvalidArgumentException
     */
    public static function fromString($amount, Currency $currency)
    {
        $decimalAmount = Decimal::fromString($amount, 0);

        if (!$decimalAmount->floor()->equals($decimalAmount)) {
            throw new InvalidArgumentException("Amount can not contains decimals");
        }

        return new self($decimalAmount, $currency);
    }
}