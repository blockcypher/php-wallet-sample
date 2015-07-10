<?php

namespace BlockCypher\AppWallet\Infrastructure\AppWalletBundle\Controller\Homepage;

use BlockCypher\AppWallet\Infrastructure\AppWalletBundle\Controller\AppWalletController;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Translation\TranslatorInterface;

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
        $data = array(
            'is_home' => true,
            'messages' => array(),
            'coin_symbol' => '',
            'user' => array('is_authenticated' => true),
        );

        $template = $this->getBaseTemplatePrefix() . ':Homepage:home.html';

        return $this->templating->renderResponse($template . '.' . $this->getEngine(), $data);
    }
}





