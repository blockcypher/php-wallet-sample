<?php

namespace BlockCypher\AppWallet\Domain\Wallet;

use BlockCypher\AppCommon\Domain\ArrayConversion;
use BlockCypher\AppCommon\Domain\Model;

/**
 * Class EncryptedFiatWallet
 * @package BlockCypher\AppWallet\Domain\Wallet
 */
class EncryptedFiatWallet extends Model implements ArrayConversion
{
    /**
     * @param array $entityAsArray
     * @return mixed
     */
    public static function fromArray($entityAsArray)
    {
        // TODO: Implement fromArray() method.
    }

    /**
     * @return array
     */
    public function toArray()
    {
        // TODO: Implement toArray() method.
    }
}