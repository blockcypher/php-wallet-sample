<?php

namespace BlockCypher\AppWallet\Infrastructure\AppWalletBundle\Controller\Transaction;

use BlockCypher\AppWallet\App\Command\CreateAddressCommand;
use BlockCypher\AppWallet\Infrastructure\AppWalletBundle\Controller\AppWalletController;
use BlockCypher\AppWallet\Infrastructure\AppWalletBundle\Form\Transaction\TransactionFormFactory;
use BlockCypher\AppWallet\Presentation\Facade\WalletServiceFacade;
use SimpleBus\Message\Bus\MessageBus;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Translation\TranslatorInterface;

class Create extends AppWalletController
{
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var TransactionFormFactory
     */
    private $transactionFormFactory;

    /**
     * @var MessageBus
     */
    private $commandBus;

    /**
     * @var WalletServiceFacade
     */
    private $walletServiceFacade;

    /**
     * @param EngineInterface $templating
     * @param TranslatorInterface $translator
     * @param Session $session
     * @param RouterInterface $router
     * @param TransactionFormFactory $transactionFormFactory
     * @param MessageBus $commandBus
     * @param WalletServiceFacade $walletServiceFacade
     */
    public function __construct(
        EngineInterface $templating,
        TranslatorInterface $translator,
        Session $session,
        RouterInterface $router,
        TransactionFormFactory $transactionFormFactory,
        MessageBus $commandBus,
        WalletServiceFacade $walletServiceFacade
    )
    {
        parent::__construct($templating, $translator, $session);
        $this->router = $router;
        $this->transactionFormFactory = $transactionFormFactory;
        $this->commandBus = $commandBus;
        $this->walletServiceFacade = $walletServiceFacade;
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function __invoke(Request $request)
    {
        $walletId = $request->get('walletId');

        $walletDto = $this->walletServiceFacade->getWallet($walletId);

        $createTransactionCommand = $this->createCreateTransactionCommand($walletId);

        $createTransactionForm = $this->transactionFormFactory->createCreateForm($createTransactionCommand);

        $createTransactionForm->handleRequest($request);

        $messages = array();

        if (!$createTransactionForm->isValid()) {

            $message = $this->trans('address_form.invalid_fields');
            $messages[] = $message;

            $template = $this->getBaseTemplatePrefix() . ':Address:show_new.html';

            // TODO: extract method. Same response when form is invalid and if there is a problem creating the tx
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

        /** @var CreateAddressCommand $createTransactionCommand */
        $createTransactionCommand = $createTransactionForm->getData();

        try {

            $this->commandBus->handle($createTransactionCommand);

            $this->addFlash('success', $this->trans('transaction.flash.create_successfully'));

            $url = $this->router->generate('bc_app_wallet_transaction.index', array('walletId' => $createTransactionCommand->getWalletId()));

            return new RedirectResponse($url);

        } catch (\Exception $e) {
            $this->session->getFlashBag()->add('error', $e->getMessage());
        }

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