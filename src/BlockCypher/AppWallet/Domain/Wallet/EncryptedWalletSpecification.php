<?php

namespace BlockCypher\AppWallet\Domain\Wallet;

/**
 * Interface EncryptedWalletSpecification.  Used for In memory repositories.
 * @package BlockCypher\AppWallet\Domain\Wallet
 */
interface EncryptedWalletSpecification
{
    /**
     * @param Wallet $wallet
     * @return bool
     */
    public function specifies(Wallet $wallet);
}