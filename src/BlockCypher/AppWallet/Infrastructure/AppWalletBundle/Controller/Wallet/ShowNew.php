<?php

namespace BlockCypher\AppWallet\Infrastructure\AppWalletBundle\Controller\Wallet;

use BlockCypher\AppWallet\Infrastructure\AppWalletBundle\Controller\AppWalletController;
use BlockCypher\AppWallet\Infrastructure\AppWalletBundle\Form\Wallet\WalletFormFactory;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Translation\TranslatorInterface;

class ShowNew extends AppWalletController
{
    /**
     * @var WalletFormFactory
     */
    private $walletFormFactory;

    /**
     * @param TokenStorageInterface $tokenStorage
     * @param EngineInterface $templating
     * @param TranslatorInterface $translator
     * @param Session $session
     * @param WalletFormFactory $walletFormFactory
     */
    public function __construct(
        TokenStorageInterface $tokenStorage,
        EngineInterface $templating,
        TranslatorInterface $translator,
        Session $session,
        WalletFormFactory $walletFormFactory)
    {
        parent::__construct($tokenStorage, $templating, $translator, $session);
        $this->walletFormFactory = $walletFormFactory;
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function __invoke(Request $request)
    {
        $user = $this->getLoggedInUser();

        if (!$user) {
            throw $this->createAccessDeniedException();
        }

        $createWalletCommand = $this->createCreateWalletCommand();

        $createWalletForm = $this->walletFormFactory->createCreateForm($createWalletCommand);

        $template = $this->getBaseTemplatePrefix() . ':Wallet:show_new.html';

        return $this->templating->renderResponse(
            $template . '.' . $this->getEngine(),
            array_merge($this->getBasicTemplateVariables($request),
                array(
                    'wallet_form' => $createWalletForm->createView(),
                )
            )
        );
    }
}