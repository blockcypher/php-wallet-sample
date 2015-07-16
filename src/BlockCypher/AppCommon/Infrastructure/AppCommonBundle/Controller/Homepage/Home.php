<?php

namespace BlockCypher\AppCommon\Infrastructure\AppCommonBundle\Controller\Homepage;

use BlockCypher\AppCommon\Infrastructure\AppCommonBundle\Controller\AppCommonController;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class Home
 * @package BlockCypher\AppCommon\Infrastructure\AppCommonBundle\Controller\Homepage
 */
class Home extends AppCommonController
{
    /**
     * @param TokenStorageInterface $tokenStorage
     * @param EngineInterface $templating
     * @param TranslatorInterface $translator
     * @param Session $session
     */
    public function __construct(
        TokenStorageInterface $tokenStorage,
        EngineInterface $templating,
        TranslatorInterface $translator,
        Session $session
    )
    {
        parent::__construct($tokenStorage, $templating, $translator, $session);
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





