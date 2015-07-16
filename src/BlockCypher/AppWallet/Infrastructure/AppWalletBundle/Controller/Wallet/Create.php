<?php

namespace BlockCypher\AppWallet\Infrastructure\AppWalletBundle\Controller\Wallet;

use BlockCypher\AppWallet\App\Command\CreateWalletCommand;
use BlockCypher\AppWallet\Infrastructure\AppWalletBundle\Controller\AppWalletController;
use BlockCypher\AppWallet\Infrastructure\AppWalletBundle\Form\Wallet\WalletFormFactory;
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

class Create extends AppWalletController
{
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var WalletFormFactory
     */
    private $walletFormFactory;

    /**
     * @var MessageBus
     */
    private $commandBus;

    /**
     * @param TokenStorageInterface $tokenStorage
     * @param EngineInterface $templating
     * @param TranslatorInterface $translator
     * @param Session $session
     * @param RouterInterface $router
     * @param WalletFormFactory $walletFormFactory
     * @param MessageBus $commandBus
     */
    public function __construct(
        TokenStorageInterface $tokenStorage,
        EngineInterface $templating,
        TranslatorInterface $translator,
        Session $session,
        RouterInterface $router,
        WalletFormFactory $walletFormFactory,
        MessageBus $commandBus)
    {
        parent::__construct($tokenStorage, $templating, $translator, $session);
        $this->router = $router;
        $this->walletFormFactory = $walletFormFactory;
        $this->commandBus = $commandBus;
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function __invoke(Request $request)
    {
        $user = $this->getLoggedInUser();

        if (!$user) {
            throw $this->createAccessDeniedException();
        }

        $createWalletCommand = $this->createCreateWalletCommand();

        $createWalletForm = $this->walletFormFactory->createCreateForm($createWalletCommand);

        $createWalletForm->handleRequest($request);

        if (!$createWalletForm->isValid()) {

            $validationMsg = $this->getAllFormErrorMessagesAsString($createWalletForm);
            $this->addFlash('error', $this->trans('create_transaction_form.flash.invalid_form') . ' ' . $validationMsg);

        } else {

            /** @var CreateWalletCommand $createWalletCommand */
            $createWalletCommand = $createWalletForm->getData();

            $createWalletCommand->setWalletOwnerId($user->getId()->getValue());
            $createWalletCommand->setToken($user->getBlockCypherToken());

            try {

                $this->commandBus->handle($createWalletCommand);

                $this->addFlash('success', $this->trans('wallet.flash.create_successfully'));

                $url = $this->router->generate('bc_app_wallet_wallet.index');

                return new RedirectResponse($url);

            } catch (\Exception $e) {
                $this->addFlash('error', $e->getMessage());
            }
        }

        return $this->renderWalletShowNew($request, $createWalletForm->createView());
    }

    /**
     * @param Request $request
     * @param FormView $createWalletFormView
     * @return Response
     */
    private function renderWalletShowNew(
        Request $request,
        FormView $createWalletFormView
    )
    {
        $template = $this->getBaseTemplatePrefix() . ':Wallet:show_new.html';

        return $this->templating->renderResponse(
            $template . '.' . $this->getEngine(),
            array_merge($this->getBasicTemplateVariables($request),
                array(
                    'wallet_form' => $createWalletFormView
                )
            )
        );
    }
}