<?php

namespace BlockCypher\AppWallet\Presentation\Utils;

use DateTime;

class DateTimeFactory
{
    /**
     * This functions fixes this bug:
     * https://bugs.php.net/bug.php?id=51950
     * This works:
     * var_dump(new \Datetime("2015-07-09T09:56:27.18208806Z", new DateTimeZone("UTC")));
     * This does not work (PHP 5.5.18):
     * var_dump(new \Datetime("2015-07-09T09:56:27.182088061Z", new DateTimeZone("UTC")));
     * Notice only one more decimal in seconds.
     *
     * It removes microseconds before calling DateTime constructor.
     *
     * TODO: add test
     *
     * @param $date
     * @param int $microSecDecimals
     * @return DateTime
     * @throws \Exception
     */
    public static function fromISO8601($date, $microSecDecimals = 0)
    {
        // Sample input date: 2015-07-09T09:56:27.182088067Z
        // Sample output date removing microseconds: 2015-07-09T09:56:27Z

        if ($date === null) {
            throw new \Exception("Invalid date format: null");
        }

        if (!is_string($date)) {
            throw new \Exception("Invalid date format: string required.");
        }

        $posPoint = strpos($date, '.');
        if ($posPoint === false) {
            // date does not contain decimal point
            return new DateTime($date);
        }

        $posZ = strpos($date, 'Z');
        if ($posZ === false) {
            throw new \Exception(sprintf("Invalid ISO86001 date %s", $date));
        }

        $decimals = substr($date, $posPoint, $posZ);
        $formattedDecimals = substr($decimals, 0, $microSecDecimals);

        $newFormattedDate = '';
        $newFormattedDate .= substr($date, 0, $posPoint);
        $newFormattedDate .= '.';
        $newFormattedDate .= $formattedDecimals;
        $newFormattedDate .= 'Z';

        return new DateTime($newFormattedDate);
    }
}