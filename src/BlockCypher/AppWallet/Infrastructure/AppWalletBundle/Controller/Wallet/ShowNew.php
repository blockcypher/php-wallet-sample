<?php

namespace BlockCypher\AppWallet\Infrastructure\AppWalletBundle\Controller\Wallet;

use BlockCypher\AppWallet\Infrastructure\AppWalletBundle\Controller\AppWalletController;
use BlockCypher\AppWallet\Infrastructure\AppWalletBundle\Form\Wallet\WalletFormFactory;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Translation\TranslatorInterface;

class ShowNew extends AppWalletController
{
    /**
     * @var WalletFormFactory
     */
    private $walletFormFactory;

    /**
     * @param EngineInterface $templating
     * @param TranslatorInterface $translator
     * @param Session $session
     * @param WalletFormFactory $walletFormFactory
     */
    public function __construct(
        EngineInterface $templating,
        TranslatorInterface $translator,
        Session $session,
        WalletFormFactory $walletFormFactory)
    {
        parent::__construct($templating, $translator, $session);
        $this->walletFormFactory = $walletFormFactory;
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function __invoke(Request $request)
    {
        $createWalletCommand = $this->createCreateWalletCommand();

        $createWalletForm = $this->walletFormFactory->createCreateForm($createWalletCommand);

        $template = $this->getBaseTemplatePrefix() . ':Wallet:show_new.html';

        return $this->templating->renderResponse(
            $template . '.' . $this->getEngine(),
            array(
                // TODO: move to base controller and merge arrays
                'is_home' => false,
                'user' => array('is_authenticated' => true),
                'messages' => array(),
                //
                'coin_symbol' => 'btc',
                'wallet_form' => $createWalletForm->createView(),
            )
        );
    }
}