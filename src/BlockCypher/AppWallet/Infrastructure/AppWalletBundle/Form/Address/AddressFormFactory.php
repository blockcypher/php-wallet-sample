<?php

namespace BlockCypher\AppWallet\Infrastructure\AppWalletBundle\Form\Address;

use BlockCypher\AppWallet\App\Command\CreateAddressCommand;
use BlockCypher\AppWallet\Domain\Account\AccountRepository;
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
     * @var AccountRepository
     */
    private $accountRepository;

    /**
     * @param FormFactoryInterface $formFactory
     * @param RouterInterface $router
     * @param TranslatorInterface $translator
     * @param AccountRepository $accountRepository
     */
    function __construct(
        FormFactoryInterface $formFactory,
        RouterInterface $router,
        TranslatorInterface $translator,
        AccountRepository $accountRepository
    )
    {
        $this->formFactory = $formFactory;
        $this->router = $router;
        $this->translator = $translator;
        $this->accountRepository = $accountRepository;
    }

    /**
     * @param CreateAddressCommand $createAddressCommand
     * @return Form The form
     */
    public function createCreateForm(CreateAddressCommand $createAddressCommand = null)
    {
        $accountChoices = $this->generateAccountHtmlSelectChoices();

        $defaultSelectedAccountId = $createAddressCommand->getAccountId();
//        if (!empty($accountId)) {
//            $defaultSelectedAccountId = $accountId;
//        }

        $form = $this->formFactory->create(
            new CreateAddressType($accountChoices, $defaultSelectedAccountId),
            $createAddressCommand, array(
            'action' => $this->router->generate('bc_app_wallet_address.create'),
            'method' => 'POST',
            'csrf_protection' => true,
        ));

        //$form->add('submit', 'submit'); // Using bootstrap button

        return $form;
    }

    /**
     * Returns an array of choices for to be used in a form select type listing accounts.
     * @return array
     */
    private function generateAccountHtmlSelectChoices()
    {
        $accountChoices = array();
        $accounts = $this->accountRepository->findAll();
        foreach ($accounts as $account) {
            $accountChoices[$account->getId()->getValue()] = $account->getTag();
        }
        return $accountChoices;
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