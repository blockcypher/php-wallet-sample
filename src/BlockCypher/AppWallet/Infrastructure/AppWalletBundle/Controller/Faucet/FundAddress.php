<?php

namespace BlockCypher\AppWallet\Infrastructure\AppWalletBundle\Controller\Faucet;

use BlockCypher\AppWallet\App\Command\FundAddressCommand;
use BlockCypher\AppWallet\App\Command\FundAddressCommandValidator;
use BlockCypher\AppWallet\Infrastructure\AppWalletBundle\Controller\AppWalletController;
use BlockCypher\AppWallet\Infrastructure\AppWalletBundle\Form\Faucet\FundAddressFormFactory;
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

class FundAddress extends AppWalletController
{
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var FundAddressFormFactory
     */
    private $fundAddressFormFactory;

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
     * @param FundAddressFormFactory $fundAddressFormFactory
     * @param MessageBus $commandBus
     */
    public function __construct(
        TokenStorageInterface $tokenStorage,
        EngineInterface $templating,
        TranslatorInterface $translator,
        Session $session,
        RouterInterface $router,
        FundAddressFormFactory $fundAddressFormFactory,
        MessageBus $commandBus)
    {
        parent::__construct($tokenStorage, $templating, $translator, $session);
        $this->router = $router;
        $this->fundAddressFormFactory = $fundAddressFormFactory;
        $this->commandBus = $commandBus;
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function __invoke(Request $request)
    {
        $user = $this->getUser();

        if (!$user) {
            throw $this->createAccessDeniedException();
        }

        $fundAddressCommand = $this->createFundAddressCommand();

        $fundAddressForm = $this->fundAddressFormFactory->createCreateForm($fundAddressCommand);

        $fundAddressForm->handleRequest($request);

        if (!$fundAddressForm->isValid()) {

            $validationMsg = $this->getAllFormErrorMessagesAsString($fundAddressForm);
            $this->addFlash('error', $this->trans('fund_address_form.flash.invalid_form') . ' ' . $validationMsg);

        } else {

            /** @var FundAddressCommand $fundAddressCommand */
            $fundAddressCommand = $fundAddressForm->getData();
            $fundAddressCommand->setToken($user->getBlockCypherToken());

            try {

                $commandValidator = new FundAddressCommandValidator();
                $commandValidator->validate($fundAddressCommand);

                $this->commandBus->handle($fundAddressCommand);

                $this->addFlash('success', $this->trans('faucet.flash.fund_address_successfully'));

                $url = $this->router->generate('bc_app_wallet_faucet.show');

                return new RedirectResponse($url);

            } catch (\Exception $e) {
                $this->addFlash('error', $e->getMessage());
            }
        }

        return $this->renderFaucetShowPage($request, $fundAddressForm->createView());
    }

    /**
     * @param Request $request
     * @param FormView $createWalletFormView
     * @return Response
     */
    private function renderFaucetShowPage(
        Request $request,
        FormView $createWalletFormView
    )
    {
        $template = $this->getBaseTemplatePrefix() . ':Faucet:show.html';

        return $this->templating->renderResponse(
            $template . '.' . $this->getEngine(),
            array_merge($this->getBasicTemplateVariables($request),
                array(
                    'fund_address_form' => $createWalletFormView
                )
            )
        );
    }
}