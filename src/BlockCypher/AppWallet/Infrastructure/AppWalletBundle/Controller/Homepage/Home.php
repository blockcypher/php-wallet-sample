<?php

namespace BlockCypher\AppWallet\Infrastructure\AppWalletBundle\Controller\Homepage;

use BlockCypher\AppWallet\Infrastructure\AppWalletBundle\Controller\AppWalletController;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class Home
 * @package BlockCypher\AppWallet\Infrastructure\AppWalletBundle\Controller\Homepage
 */
class Home extends AppWalletController
{
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
        parent::__construct($templating, $translator, $session);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function __invoke(Request $request)
    {
        $template = $this->getBaseTemplatePrefix() . ':Homepage:home.html';

        return $this->templating->renderResponse(
            $template . '.' . $this->getEngine(),
            array_merge($this->getBasicTemplateVariables($request),
                array(
                    'is_home' => true,
                )
            )
        );
    }
}





