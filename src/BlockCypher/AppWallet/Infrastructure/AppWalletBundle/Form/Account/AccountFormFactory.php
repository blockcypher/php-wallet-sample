<?php

namespace BlockCypher\AppWallet\Infrastructure\AppWalletBundle\Form\Account;

use BlockCypher\AppWallet\App\Command\CreateAccountCommand;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class AccountFormFactory
 * @package BlockCypher\AppWallet\Infrastructure\AppWalletBundle\Form\Account
 */
class AccountFormFactory
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
     * @param CreateAccountCommand $createAccountCommand
     * @return Form The form
     */
    public function createCreateForm(CreateAccountCommand $createAccountCommand = null)
    {
        $form = $this->formFactory->create(new CreateAccountType(), $createAccountCommand, array(
            'action' => $this->router->generate('bc_app_wallet_account.create'),
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
    private function trans($id, array $parameters = array(), $domain = 'BlockCypherAppWalletInfrastructureAppWalletBundle', $locale = null)
    {
        return $this->translator->trans($id, $parameters, $domain, $locale);
    }
}