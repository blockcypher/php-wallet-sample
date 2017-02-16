<?php

namespace BlockCypher\AppWallet\Infrastructure\AppWalletBundle\Controller\Transaction;

use BlockCypher\AppWallet\Infrastructure\AppWalletBundle\Controller\AppWalletController;
use BlockCypher\AppWallet\Infrastructure\AppWalletBundle\Form\Transaction\TransactionFormFactory;
use BlockCypher\AppWallet\Presentation\Facade\WalletServiceFacade;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class ShowNew
 * @package BlockCypher\AppWallet\Infrastructure\AppWalletBundle\Controller\Transaction
 */
class ShowNew extends AppWalletController
{
    /**
     * @var TransactionFormFactory
     */
    private $transactionFormFactory;

    /**
     * @param TokenStorageInterface $tokenStorage
     * @param EngineInterface $templating
     * @param TranslatorInterface $translator
     * @param Session $session
     * @param TransactionFormFactory $transactionFormFactory
     * @param WalletServiceFacade $fundAddressServiceFacade
     */
    public function __construct(
        TokenStorageInterface $tokenStorage,
        EngineInterface $templating,
        TranslatorInterface $translator,
        Session $session,
        TransactionFormFactory $transactionFormFactory,
        WalletServiceFacade $fundAddressServiceFacade
    )
    {
        parent::__construct($tokenStorage, $templating, $translator, $session);
        $this->transactionFormFactory = $transactionFormFactory;
        $this->walletServiceFacade = $fundAddressServiceFacade;
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

        // Default form data
        $createTransactionCommand = $this->createCreateTransactionCommand(
            $walletId,
            "mwmabpJVisvti3WEP5vhFRtn3yqHRD9KNP", // BTC Testnet faucet return address
            "Your transaction description",
            1000
        );

        $user = $this->getUser();

        $createTransactionForm = $this->transactionFormFactory->createCreateForm($createTransactionCommand, $user->getId()->getValue());

        $template = $this->getBaseTemplatePrefix() . ':Transaction:show_new.html';

        return $this->templating->renderResponse(
            $template . '.' . $this->getEngine(),
            array_merge($this->getBasicTemplateVariables($request),
                array(
                    'transaction_form' => $createTransactionForm->createView(),
                    'wallet' => $walletDto,
                )
            )
        );
    }
}