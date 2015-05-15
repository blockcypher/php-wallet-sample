<?php

namespace BlockCypher\AppCommon\Infrastructure\LayoutBundle\Twig;

use BlockCypher\AppCommon\App\Service\Internal\BlockCypherCoinSymbol;
use BlockCypher\Converter\BtcConverter;

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
        /*$displayShortname = '';
        if (isset(BlockCypherApiConstants::$COIN_SYMBOL_MAPPINGS[$coinSymbol]['display_shortname'])) {
            $displayShortname = BlockCypherApiConstants::$COIN_SYMBOL_MAPPINGS[$coinSymbol]['display_shortname'];
        }
        return $displayShortname;*/
        return BlockCypherCoinSymbol::getDisplayShortname($coinSymbol);
    }

    /**
     * @param $coinSymbol
     * @return string
     */
    public function coinSymbolToDisplayNameFilter($coinSymbol)
    {
        /*$displayName = '';
        if (isset(BlockCypherApiConstants::$COIN_SYMBOL_MAPPINGS[$coinSymbol]['display_name'])) {
            $displayName = BlockCypherApiConstants::$COIN_SYMBOL_MAPPINGS[$coinSymbol]['display_name'];
        }
        return $displayName;*/
        return BlockCypherCoinSymbol::getDisplayName($coinSymbol);
    }

    /**
     * @param string $coinSymbol
     * @return string
     */
    public function coinSymbolToCurrencyNameFilter($coinSymbol)
    {
        /*$currencyAbbrev = '';
        if (isset(BlockCypherApiConstants::$COIN_SYMBOL_MAPPINGS[$coinSymbol]['currency_abbrev'])) {
            $currencyAbbrev = BlockCypherApiConstants::$COIN_SYMBOL_MAPPINGS[$coinSymbol]['currency_abbrev'];
        }
        return $currencyAbbrev;*/
        return BlockCypherCoinSymbol::getCurrencyAbbrev($coinSymbol);
    }

    /**
     * @param string $coinSymbol
     * @return string
     */
    public function coinSymbolToWssFilter($coinSymbol)
    {
        /*$blockcypherCode = '';
        $blockcypherNetwork  = '';
        if (isset(BlockCypherApiConstants::$COIN_SYMBOL_MAPPINGS[$coinSymbol]['blockcypher_code'])) {
            $blockcypherCode = BlockCypherApiConstants::$COIN_SYMBOL_MAPPINGS[$coinSymbol]['blockcypher_code'];
        }
        if (isset(BlockCypherApiConstants::$COIN_SYMBOL_MAPPINGS[$coinSymbol]['blockcypher_network'])) {
            $blockcypherNetwork = BlockCypherApiConstants::$COIN_SYMBOL_MAPPINGS[$coinSymbol]['blockcypher_network'];
        }

        if (empty($blockcypherCode) || empty($blockcypherNetwork)) {
            // Invalid $coinSymbol or not present in BlockCypherApiConstants
            return "";
        }*/

        $blockcypherCode = BlockCypherCoinSymbol::getBlockCypherCode($coinSymbol);
        $blockcypherNetwork = BlockCypherCoinSymbol::getBlockCypherNetwork($coinSymbol);

        // TODO: move to SDK
        return "wss://socket.blockcypher.com/v1/{$blockcypherCode}/{$blockcypherNetwork}";
    }

    /**
     * @param $amount
     * @return string
     */
    public function satoshisToBtcRoundingFilter($amount)
    {
        $value = BtcConverter::satoshisToBtcRounded($amount);
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
     * @return string
     */
    public function intCommaFilter($number)
    {
        $decimalPoint = ',';
        $thousandSep = '.';
        if (is_int($number)) {
            $value = number_format((float)$number, 0, $decimalPoint, $thousandSep);
        } elseif (!$this->is_decimal($number)) {
            $value = number_format((float)$number, 1, $decimalPoint, $thousandSep);
        } else {
            $value = number_format((float)$number, 4, $decimalPoint, $thousandSep);
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