<?php

namespace BlockCypher\AppWallet\Infrastructure\AppWalletBundle\Controller;

use BlockCypher\AppCommon\Infrastructure\Controller\AppCommonController;
use BlockCypher\AppWallet\App\Command\CreateAddressCommand;

class AppWalletController extends AppCommonController
{
    public function getBaseTemplatePrefix()
    {
        return 'BlockCypherAppWalletInfrastructureAppWalletBundle';
    }

    /**
     * @param $accountId
     * @param $tag
     * @param $callbackUrl
     * @return CreateAddressCommand
     */
    protected function createCreateAddressCommand($accountId, $tag, $callbackUrl)
    {
        $createAddressCommand = new CreateAddressCommand($accountId, $tag, $callbackUrl);
        return $createAddressCommand;
    }

    /**
     * Shortcut to trans. Consider to put it in some common parent controller.
     * @param $id
     * @param array $parameters
     * @param string $domain
     * @param null $locale
     * @return string
     */
    protected function trans(
        $id,
        $parameters = array(),
        $domain = 'BlockCypherAppWalletInfrastructureAppWalletBundle',
        $locale = null)
    {
        return $this->translator->trans($id, $parameters, $domain, $locale);
    }
}