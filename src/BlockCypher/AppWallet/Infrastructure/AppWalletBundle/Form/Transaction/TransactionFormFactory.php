<?php

namespace BlockCypher\AppWallet\Infrastructure\AppWalletBundle\Form\Transaction;

use BlockCypher\AppWallet\App\Command\CreateTransactionCommand;
use BlockCypher\AppWallet\Domain\Wallet\WalletRepository;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class TransactionFormFactory
 * @package BlockCypher\AppWallet\Infrastructure\AppWalletBundle\Form\Transaction
 */
class TransactionFormFactory
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
     * @param CreateTransactionCommand $createTransactionCommand
     * @return Form The form
     */
    public function createCreateForm(CreateTransactionCommand $createTransactionCommand = null)
    {
        $walletChoices = $this->generateWalletHtmlSelectChoices();
        $defaultSelectedWalletId = $createTransactionCommand->getWalletId();

        $form = $this->formFactory->create(
            new CreateTransactionType($walletChoices, $defaultSelectedWalletId),
            $createTransactionCommand,
            array(
                'action' => $this->router->generate('bc_app_wallet_transaction.create', array('walletId' => $defaultSelectedWalletId)),
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
}