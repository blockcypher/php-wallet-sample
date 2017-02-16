<?php

namespace BlockCypher\AppWallet\Infrastructure\AppWalletBundle\Form\Faucet;

use BlockCypher\AppWallet\App\Command\FundAddressCommand;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class FundAddressFormFactory
 * @package BlockCypher\AppWallet\Infrastructure\AppWalletBundle\Form\Wallet
 */
class FundAddressFormFactory
{
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @param FormFactoryInterface $formFactory
     * @param RouterInterface $router
     * @param TranslatorInterface $translator
     */
    function __construct(
        FormFactoryInterface $formFactory,
        RouterInterface $router,
        TranslatorInterface $translator
    )
    {
        $this->formFactory = $formFactory;
        $this->router = $router;
        $this->translator = $translator;
    }

    /**
     * @param FundAddressCommand|null $fundAddressCommand
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createCreateForm(FundAddressCommand $fundAddressCommand = null)
    {
        $form = $this->formFactory->create(new FundAddressType(), $fundAddressCommand, array(
            'action' => $this->router->generate('bc_app_wallet_faucet.fund_address'),
            'method' => 'POST',
            'csrf_protection' => false, // TODO: activate
        ));
        return $form;
    }
}