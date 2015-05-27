<?php

namespace BlockCypher\AppCommon\Infrastructure\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Translation\TranslatorInterface;

class AppCommonController extends Controller
{
    /**
     * @var EngineInterface
     */
    protected $templating;

    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * @param EngineInterface $templating
     * @param TranslatorInterface $translator
     */
    public function __construct(
        EngineInterface $templating,
        TranslatorInterface $translator
    )
    {
        $this->templating = $templating;
        $this->translator = $translator;
    }

    /**
     * @return string
     */
    protected function getEngine()
    {
        return 'twig';
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