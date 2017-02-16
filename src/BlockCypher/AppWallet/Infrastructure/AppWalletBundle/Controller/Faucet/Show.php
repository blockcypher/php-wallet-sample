<?php

namespace BlockCypher\AppWallet\Infrastructure\AppWalletBundle\Controller\Faucet;

use BlockCypher\AppWallet\Infrastructure\AppWalletBundle\Controller\AppWalletController;
use BlockCypher\AppWallet\Infrastructure\AppWalletBundle\Form\Faucet\FundAddressFormFactory;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Translation\TranslatorInterface;

class Show extends AppWalletController
{
    /**
     * @var FundAddressFormFactory
     */
    private $fundAddressFormFactory;

    /**
     * @param TokenStorageInterface $tokenStorage
     * @param EngineInterface $templating
     * @param TranslatorInterface $translator
     * @param Session $session
     * @param FundAddressFormFactory $fundAddressFormFactory
     */
    public function __construct(
        TokenStorageInterface $tokenStorage,
        EngineInterface $templating,
        TranslatorInterface $translator,
        Session $session,
        FundAddressFormFactory $fundAddressFormFactory)
    {
        parent::__construct($tokenStorage, $templating, $translator, $session);
        $this->fundAddressFormFactory = $fundAddressFormFactory;
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function __invoke(Request $request)
    {
        $user = $this->getUser();

        if (!$user) {
            throw $this->createAccessDeniedException();
        }

        $fundAddressCommand = $this->createFundAddressCommand(
            $request->get('address'),
            (int)$request->get('amount'),
            $request->get('coinSymbol')
        );

        $fundAddressForm = $this->fundAddressFormFactory->createCreateForm($fundAddressCommand);

        $template = $this->getBaseTemplatePrefix() . ':Faucet:show.html';

        return $this->templating->renderResponse(
            $template . '.' . $this->getEngine(),
            array_merge($this->getBasicTemplateVariables($request),
                array(
                    'fund_address_form' => $fundAddressForm->createView(),
                )
            )
        );
    }
}