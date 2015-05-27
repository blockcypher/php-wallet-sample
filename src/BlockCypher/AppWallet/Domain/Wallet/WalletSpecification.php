<?php

namespace BlockCypher\AppWallet\Domain\Wallet;

/**
 * Interface WalletSpecification.  Used for In memory repositories.
 * @package BlockCypher\AppWallet\Domain\Wallet
 */
interface WalletSpecification
{
    /**
     * @param Wallet $wallet
     * @return bool
     */
    public function specifies(Wallet $wallet);
}