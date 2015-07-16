<?php

namespace BlockCypher\AppWallet\Infrastructure\AppWalletBundle\Controller\Address;

use BlockCypher\AppWallet\App\Command\CreateAddressCommand;
use BlockCypher\AppWallet\Infrastructure\AppWalletBundle\Controller\AppWalletController;
use BlockCypher\AppWallet\Infrastructure\AppWalletBundle\Form\Address\AddressFormFactory;
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
 * Class Generate
 * @package BlockCypher\AppWallet\Infrastructure\AppWalletBundle\Controller\Address
 */
class Generate extends AppWalletController
{
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var AddressFormFactory
     */
    private $addressFormFactory;

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
     * @param AddressFormFactory $addressFormFactory
     * @param MessageBus $commandBus
     * @param WalletServiceFacade $walletServiceFacade
     */
    public function __construct(
        TokenStorageInterface $tokenStorage,
        EngineInterface $templating,
        TranslatorInterface $translator,
        Session $session,
        RouterInterface $router,
        AddressFormFactory $addressFormFactory,
        MessageBus $commandBus,
        WalletServiceFacade $walletServiceFacade
    )
    {
        parent::__construct($tokenStorage, $templating, $translator, $session);
        $this->router = $router;
        $this->addressFormFactory = $addressFormFactory;
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

        $this->checkAuthorizationForWallet($walletDto);

        $createAddressCommand = $this->createCreateAddressCommand($walletId);

        $createAddressForm = $this->addressFormFactory->createCreateForm($createAddressCommand);

        $createAddressForm->handleRequest($request);

        if (!$createAddressForm->isValid()) {

            $validationMsg = $this->getAllFormErrorMessagesAsString($createAddressForm);
            $this->addFlash('error', $this->trans('create_address_form.flash.invalid_form') . ' ' . $validationMsg);

        } else {

            /** @var CreateAddressCommand $createAddressCommand */
            $createAddressCommand = $createAddressForm->getData();

            try {

                $this->commandBus->handle($createAddressCommand);

                $this->addFlash('success', $this->trans('address.flash.create_successfully'));

                $url = $this->router->generate('bc_app_wallet_address.index', array('walletId' => $createAddressCommand->getWalletId()));

                return new RedirectResponse($url);

            } catch (\Exception $e) {
                $this->addFlash('error', $e->getMessage());
            }
        }

        $walletDto = $this->walletServiceFacade->getWallet($walletId);

        return $this->renderAddressShowNew(
            $request,
            $createAddressForm->createView(),
            $walletDto
        );
    }

    /**
     * @param Request $request
     * @param FormView $createAddressFormView
     * @param WalletDto $walletDto
     * @return Response
     */
    private function renderAddressShowNew(
        Request $request,
        FormView $createAddressFormView,
        WalletDto $walletDto
    )
    {
        $template = $this->getBaseTemplatePrefix() . ':Address:show_new.html';

        return $this->templating->renderResponse(
            $template . '.' . $this->getEngine(),
            array_merge($this->getBasicTemplateVariables($request),
                array(
                    'address_form' => $createAddressFormView,
                    'wallet' => $walletDto,
                )
            )
        );
    }
}