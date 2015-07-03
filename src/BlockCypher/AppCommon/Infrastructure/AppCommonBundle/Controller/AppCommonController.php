<?php

namespace BlockCypher\AppCommon\Infrastructure\AppCommonBundle\Controller;

use BlockCypher\AppCommon\Infrastructure\Controller\AppCommonController as BaseController;

class AppCommonController extends BaseController
{
    public function getBaseTemplatePrefix()
    {
        return 'BlockCypherAppCommonInfrastructureAppCommonBundle';
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