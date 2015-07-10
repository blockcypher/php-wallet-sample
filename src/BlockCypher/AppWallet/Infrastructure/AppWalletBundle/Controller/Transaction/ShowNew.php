<?php

namespace BlockCypher\AppWallet\Infrastructure\AppWalletBundle\Controller\Transaction;

use BlockCypher\AppWallet\Infrastructure\AppWalletBundle\Controller\AppWalletController;
use BlockCypher\AppWallet\Infrastructure\AppWalletBundle\Form\Transaction\TransactionFormFactory;
use BlockCypher\AppWallet\Presentation\Facade\WalletServiceFacade;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Translation\TranslatorInterface;

class ShowNew extends AppWalletController
{
    /**
     * @var TransactionFormFactory
     */
    private $transactionFormFactory;

    /**
     * @param EngineInterface $templating
     * @param TranslatorInterface $translator
     * @param Session $session
     * @param TransactionFormFactory $transactionFormFactory
     * @param WalletServiceFacade $walletServiceFacade
     */
    public function __construct(
        EngineInterface $templating,
        TranslatorInterface $translator,
        Session $session,
        TransactionFormFactory $transactionFormFactory,
        WalletServiceFacade $walletServiceFacade
    )
    {
        parent::__construct($templating, $translator, $session);
        $this->transactionFormFactory = $transactionFormFactory;
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

        $createTransactionCommand = $this->createCreateTransactionCommand(
            $walletId,
            "n3D2YXwvpoPg8FhcWpzJiS3SvKKGD8AXZ4", // BTC Testnet
            "Description",
            1000
        );

        $createTransactionForm = $this->transactionFormFactory->createCreateForm($createTransactionCommand, $walletId);

        $template = $this->getBaseTemplatePrefix() . ':Transaction:show_new.html';

        return $this->templating->renderResponse(
            $template . '.' . $this->getEngine(),
            array(
                // TODO: move to base controller and merge arrays
                'is_home' => false,
                'user' => array('is_authenticated' => true),
                'messages' => array(),
                //
                'coin_symbol' => 'btc',
                'transaction_form' => $createTransactionForm->createView(),
                'wallet_id' => $walletId,
                'wallet' => $walletDto,
            )
        );
    }
}