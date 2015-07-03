<?php

namespace BlockCypher\AppWallet\Infrastructure\AppWalletBundle\Form\Address;

use BlockCypher\AppWallet\App\Command\CreateAddressCommand;
use BlockCypher\AppWallet\Domain\Wallet\WalletRepository;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class AddressFormFactory
 * @package BlockCypher\AppWallet\Infrastructure\AppWalletBundle\Form\Address
 */
class AddressFormFactory
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
     * @var WalletRepository
     */
    private $walletRepository;

    /**
     * @param FormFactoryInterface $formFactory
     * @param RouterInterface $router
     * @param TranslatorInterface $translator
     * @param WalletRepository $walletRepository
     */
    function __construct(
        FormFactoryInterface $formFactory,
        RouterInterface $router,
        TranslatorInterface $translator,
        WalletRepository $walletRepository
    )
    {
        $this->formFactory = $formFactory;
        $this->router = $router;
        $this->translator = $translator;
        $this->walletRepository = $walletRepository;
    }

    /**
     * @param CreateAddressCommand $createAddressCommand
     * @return Form The form
     */
    public function createCreateForm(CreateAddressCommand $createAddressCommand = null)
    {
        $walletChoices = $this->generateWalletHtmlSelectChoices();
        $defaultSelectedWalletId = $createAddressCommand->getWalletId();

        $form = $this->formFactory->create(
            new CreateAddressType($walletChoices, $defaultSelectedWalletId),
            $createAddressCommand,
            array(
                'action' => $this->router->generate('bc_app_wallet_address.generate', array('walletId' => $defaultSelectedWalletId)),
                'method' => 'POST',
                'csrf_protection' => true,
        ));

        //$form->add('submit', 'submit'); // Using bootstrap button

        return $form;
    }

    /**
     * Returns an array of choices for to be used in a form select type listing wallets.
     * @return array
     */
    private function generateWalletHtmlSelectChoices()
    {
        $walletChoices = array();
        $wallets = $this->walletRepository->findAll();
        foreach ($wallets as $wallet) {
            $walletChoices[$wallet->getId()->getValue()] = $wallet->getName();
        }
        return $walletChoices;
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