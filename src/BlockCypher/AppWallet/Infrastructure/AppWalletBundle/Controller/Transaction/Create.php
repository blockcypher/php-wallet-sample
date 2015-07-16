<?php

namespace BlockCypher\AppWallet\Infrastructure\AppWalletBundle\Controller\Transaction;

use BlockCypher\AppWallet\App\Command\CreateAddressCommand;
use BlockCypher\AppWallet\Infrastructure\AppWalletBundle\Controller\AppWalletController;
use BlockCypher\AppWallet\Infrastructure\AppWalletBundle\Form\Transaction\TransactionFormFactory;
use BlockCypher\AppWallet\Presentation\Facade\Dto\WalletDto;
use BlockCypher\AppWallet\Presentation\Facade\WalletServiceFacade;
use SimpleBus\Message\Bus\MessageBus;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class Create
 * @package BlockCypher\AppWallet\Infrastructure\AppWalletBundle\Controller\Transaction
 */
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
     * @param TokenStorageInterface $tokenStorage
     * @param EngineInterface $templating
     * @param TranslatorInterface $translator
     * @param Session $session
     * @param RouterInterface $router
     * @param TransactionFormFactory $transactionFormFactory
     * @param MessageBus $commandBus
     * @param WalletServiceFacade $walletServiceFacade
     */
    public function __construct(
        TokenStorageInterface $tokenStorage,
        EngineInterface $templating,
        TranslatorInterface $translator,
        Session $session,
        RouterInterface $router,
        TransactionFormFactory $transactionFormFactory,
        MessageBus $commandBus,
        WalletServiceFacade $walletServiceFacade
    )
    {
        parent::__construct($tokenStorage, $templating, $translator, $session);
        $this->router = $router;
        $this->transactionFormFactory = $transactionFormFactory;
        $this->commandBus = $commandBus;
        $this->walletServiceFacade = $walletServiceFacade;
    }

    /**
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function __invoke(Request $request)
    {
        $walletId = $request->get('walletId');

        $walletDto = $this->walletServiceFacade->getWallet($walletId);

        $this->checkAuthorizationForWallet($walletDto);

        $createTransactionCommand = $this->createCreateTransactionCommand($walletId);

        $user = $this->getLoggedInUser();
        $createTransactionForm = $this->transactionFormFactory->createCreateForm($createTransactionCommand, $user->getId()->getValue());

        $createTransactionForm->handleRequest($request);

        if (!$createTransactionForm->isValid()) {

            $validationMsg = $this->getAllFormErrorMessagesAsString($createTransactionForm);
            $this->addFlash('error', $this->trans('create_transaction_form.flash.invalid_form') . ' ' . $validationMsg);

        } else {

            /** @var CreateAddressCommand $createTransactionCommand */
            $createTransactionCommand = $createTransactionForm->getData();

            try {

                $this->commandBus->handle($createTransactionCommand);

                $this->addFlash('success', $this->trans('transaction.flash.create_successfully'));

                $url = $this->router->generate('bc_app_wallet_transaction.index', array('walletId' => $createTransactionCommand->getWalletId()));

                return new RedirectResponse($url);

            } catch (\Exception $e) {
                $this->addFlash('error', $e->getMessage());
            }
        }

        $walletDto = $this->walletServiceFacade->getWallet($walletId);

        return $this->renderTransactionShowNew(
            $request,
            $createTransactionForm->createView(),
            $walletDto
        );
    }

    /**
     * @param Request $request
     * @param FormView $createTransactionFormView
     * @param WalletDto $walletDto
     * @return \Symfony\Component\HttpFoundation\Response
     */
    private function renderTransactionShowNew(
        Request $request,
        FormView $createTransactionFormView,
        WalletDto $walletDto
    )
    {
        $template = $this->getBaseTemplatePrefix() . ':Transaction:show_new.html';

        return $this->templating->renderResponse(
            $template . '.' . $this->getEngine(),
            array_merge($this->getBasicTemplateVariables($request),
                array(
                    'transaction_form' => $createTransactionFormView,
                    'wallet' => $walletDto,
                )
            )
        );
    }
}