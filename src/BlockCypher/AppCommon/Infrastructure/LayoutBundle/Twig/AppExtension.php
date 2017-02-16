<?php

namespace BlockCypher\AppCommon\Infrastructure\LayoutBundle\Twig;

use BlockCypher\Converter\BtcConverter;
use BlockCypher\Core\BlockCypherCoinSymbolConstants;
use BlockCypher\Core\BlockCypherConstants;

class AppExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('coin_symbol_to_display_shortname', array($this, 'coinSymbolToDisplayShortnameFilter')),
            new \Twig_SimpleFilter('coin_symbol_to_display_name', array($this, 'coinSymbolToDisplayNameFilter')),
            new \Twig_SimpleFilter('coin_symbol_to_currency_name', array($this, 'coinSymbolToCurrencyNameFilter')),
            new \Twig_SimpleFilter('coin_symbol_to_wss', array($this, 'coinSymbolToWssFilter')),
            new \Twig_SimpleFilter('satoshis_to_btc_rounding', array($this, 'satoshisToBtcRoundingFilter')),
            new \Twig_SimpleFilter('satoshis_to_btc_full', array($this, 'satoshisToBtcFullFilter')),
            new \Twig_SimpleFilter('intcomma', array($this, 'intCommaFilter')),
            new \Twig_SimpleFilter('floatformat', array($this, 'floatFormatFilter')),
            new \Twig_SimpleFilter('add', array($this, 'addFilter')),
        );
    }

    /**
     * @param $coinSymbol
     * @return string
     */
    public function coinSymbolToDisplayShortnameFilter($coinSymbol)
    {
        return BlockCypherCoinSymbolConstants::getDisplayShortname($coinSymbol);
    }

    /**
     * @param $coinSymbol
     * @return string
     */
    public function coinSymbolToDisplayNameFilter($coinSymbol)
    {
        return BlockCypherCoinSymbolConstants::getDisplayName($coinSymbol);
    }

    /**
     * @param string $coinSymbol
     * @return string
     */
    public function coinSymbolToCurrencyNameFilter($coinSymbol)
    {
        return BlockCypherCoinSymbolConstants::getCurrencyAbbrev($coinSymbol);
    }

    /**
     * @param string $coinSymbol
     * @return string
     */
    public function coinSymbolToWssFilter($coinSymbol)
    {
        $blockcypherCode = BlockCypherCoinSymbolConstants::getBlockCypherCode($coinSymbol);
        $blockcypherNetwork = BlockCypherCoinSymbolConstants::getBlockCypherNetwork($coinSymbol);
        return BlockCypherConstants::WEB_SOCKET_LIVE_ENDPOINT . "v1/{$blockcypherCode}/{$blockcypherNetwork}";
    }

    /**
     * @param $amount
     * @return string
     */
    public function satoshisToBtcRoundingFilter($amount)
    {
        $value = BtcConverter::satoshisToBtcRounded($amount, 0);
        return $value;
    }

    /**
     * @param $amount
     * @return string
     */
    public function satoshisToBtcFullFilter($amount)
    {
        $value = BtcConverter::satoshisToBtc($amount);
        return $value;
    }

    /**
     * @param $number
     * @param int $decimals
     * @return string
     */
    public function intCommaFilter($number, $decimals = 8)
    {
        $decimalPoint = '.';
        $thousandSep = ',';
        if (is_int($number)) {
            $value = number_format((float)$number, 0, $decimalPoint, $thousandSep);
        } elseif (!$this->is_decimal($number)) {
            $value = number_format((float)$number, 1, $decimalPoint, $thousandSep);
        } else {
            $value = number_format((float)$number, $decimals, $decimalPoint, $thousandSep);
        }
        return $value;
    }

    private function is_decimal($val)
    {
        return is_numeric($val) && floor($val) != $val;
    }

    /**
     * @param $number
     * @param int $decimals
     * @return string
     */
    public function floatFormatFilter($number, $decimals)
    {
        return round($number, $decimals);
    }

    /**
     * @param $number
     * @param $value
     * @return mixed
     */
    public function addFilter($number, $value)
    {
        return $number + $value;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'app_extension';
    }
}