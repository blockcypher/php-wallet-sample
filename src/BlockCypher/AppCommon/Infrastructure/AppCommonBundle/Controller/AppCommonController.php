<?php

namespace BlockCypher\AppCommon\Infrastructure\AppCommonBundle\Controller;

use BlockCypher\AppCommon\Infrastructure\Controller\AppCommonController as BaseController;
use BlockCypher\AppWallet\App\Command\CreateAddressCommand;

class AppCommonController extends BaseController
{
    public function getBaseTemplatePrefix()
    {
        return 'BlockCypherAppCommonInfrastructureAppCommonBundle';
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
        $domain = 'BlockCypherAppCommonInfrastructureAppCommonBundle',
        $locale = null)
    {
        return $this->translator->trans($id, $parameters, $domain, $locale);
    }
}