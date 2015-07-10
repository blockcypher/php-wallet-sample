<?php

namespace BlockCypher\AppCommon\Infrastructure\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class AppCommonController
 * @package BlockCypher\AppCommon\Infrastructure\Controller
 */
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
     * @var Session
     */
    protected $session;

    /**
     * @param EngineInterface $templating
     * @param TranslatorInterface $translator
     * @param Session $session
     */
    public function __construct(
        EngineInterface $templating,
        TranslatorInterface $translator,
        Session $session
    )
    {
        $this->templating = $templating;
        $this->translator = $translator;
        $this->session = $session;
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

    /**
     * Adds a flash message to the current session for type.
     *
     * @param string $type The type
     * @param string $message The message
     *
     * @throws \LogicException
     */
    protected function addFlash($type, $message)
    {
        $this->session->getFlashBag()->add($type, $message);
    }
}