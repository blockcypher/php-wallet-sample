<?php

namespace BlockCypher\AppWallet\Infrastructure\AppWalletBundle\Controller\Address;

use BlockCypher\AppWallet\Infrastructure\AppWalletBundle\Controller\AppWalletController;
use BlockCypher\AppWallet\Infrastructure\AppWalletBundle\Form\Address\AddressFormFactory;
use BlockCypher\AppWallet\Presentation\Facade\WalletServiceFacade;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class ShowNew
 * @package BlockCypher\AppWallet\Infrastructure\AppWalletBundle\Controller\Address
 */
class ShowNew extends AppWalletController
{
    /**
     * @var AddressFormFactory
     */
    private $addressFormFactory;

    /**
     * @param TokenStorageInterface $tokenStorage
     * @param EngineInterface $templating
     * @param TranslatorInterface $translator
     * @param Session $session
     * @param AddressFormFactory $walletFormFactory
     * @param WalletServiceFacade $walletServiceFacade
     */
    public function __construct(
        TokenStorageInterface $tokenStorage,
        EngineInterface $templating,
        TranslatorInterface $translator,
        Session $session,
        AddressFormFactory $walletFormFactory,
        WalletServiceFacade $walletServiceFacade
    )
    {
        parent::__construct($tokenStorage, $templating, $translator, $session);
        $this->addressFormFactory = $walletFormFactory;
        $this->walletServiceFacade = $walletServiceFacade;
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function __invoke(Request $request)
    {
        $walletId = $request->get('walletId');

        $walletDto = $this->walletServiceFacade->getWallet($walletId);

        $this->checkAuthorizationForWallet($walletDto);

        $createAddressCommand = $this->createCreateAddressCommand($walletId);

        $createAddressForm = $this->addressFormFactory->createCreateForm($createAddressCommand, $walletId);

        $template = $this->getBaseTemplatePrefix() . ':Address:show_new.html';

        return $this->templating->renderResponse(
            $template . '.' . $this->getEngine(),
            array_merge($this->getBasicTemplateVariables($request),
                array(
                    'address_form' => $createAddressForm->createView(),
                    'wallet' => $walletDto,
                )
            )
        );
    }
}