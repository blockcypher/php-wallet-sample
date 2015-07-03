<?php

namespace BlockCypher\AppWallet\Infrastructure\AppWalletBundle\Form\Wallet;

use BlockCypher\AppWallet\App\Command\CreateWalletCommand;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class WalletFormFactory
 * @package BlockCypher\AppWallet\Infrastructure\AppWalletBundle\Form\Wallet
 */
class WalletFormFactory
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
     * @param CreateWalletCommand $createWalletCommand
     * @return Form The form
     */
    public function createCreateForm(CreateWalletCommand $createWalletCommand = null)
    {
        $form = $this->formFactory->create(new CreateWalletType(), $createWalletCommand, array(
            'action' => $this->router->generate('bc_app_wallet_wallet.create'),
            'method' => 'POST',
            'csrf_protection' => false, // TODO: activate
        ));

        //$form->add('submit', 'submit'); // Using bootstrap

        return $form;
    }

    /**
     * Shortcut to trans. Consider to put it in some common parent controller.
     * @param $id
     * @param array $parameters
     * @param string $domain
     * @param null $locale
     * @return string
     */
//    private function trans($id, array $parameters = array(), $domain = 'BlockCypherAppWalletInfrastructureAppWalletBundle', $locale = null)
//    {
//        return $this->translator->trans($id, $parameters, $domain, $locale);
//    }
}