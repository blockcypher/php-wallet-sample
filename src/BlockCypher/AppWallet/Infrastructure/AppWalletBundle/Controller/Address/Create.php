<?php

namespace BlockCypher\AppWallet\Infrastructure\AppWalletBundle\Controller\Address;

use BlockCypher\AppWallet\App\Command\CreateAddressCommand;
use BlockCypher\AppWallet\Infrastructure\AppWalletBundle\Controller\AppWalletController;
use BlockCypher\AppWallet\Infrastructure\AppWalletBundle\Form\AddressFormFactory;
use SimpleBus\Message\Bus\MessageBus;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Translation\TranslatorInterface;

class Create extends AppWalletController
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
     * @param EngineInterface $templating
     * @param TranslatorInterface $translator
     * @param RouterInterface $router
     * @param AddressFormFactory $addressFormFactory
     * @param MessageBus $commandBus
     */
    public function __construct(
        EngineInterface $templating,
        TranslatorInterface $translator,
        RouterInterface $router,
        AddressFormFactory $addressFormFactory,
        MessageBus $commandBus)
    {
        parent::__construct($templating, $translator);
        $this->router = $router;
        $this->addressFormFactory = $addressFormFactory;
        $this->commandBus = $commandBus;
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function __invoke(Request $request)
    {
        $createAddressCommand = $this->createCreateAddressCommand('', '', '');

        $createAddressForm = $this->addressFormFactory->createCreateForm($createAddressCommand);

        $createAddressForm->handleRequest($request);

        $messages = array();

        if (!$createAddressForm->isValid()) {

            $message = $this->trans('address_form.invalid_fields');
            $messages[] = $message;

            $template = $this->getBaseTemplatePrefix() . ':Address:show_new.html';

            return $this->templating->renderResponse(
                $template . '.' . $this->getEngine(),
                array(
                    // TODO: move to base controller and merge arrays
                    'is_home' => false,
                    'user' => array('is_authenticated' => true),
                    'messages' => array(),
                    //
                    'coin_symbol' => 'btc',
                    'address_form' => $createAddressForm->createView(),
                )
            );
        }

        /** @var CreateAddressCommand $createAddressCommand */
        $createAddressCommand = $createAddressForm->getData();

        try {

            $this->commandBus->handle($createAddressCommand);

            $message = $this->trans('address.flash.create_successfully');

            // TODO: add account_id parameter
            $url = $this->router->generate('bc_app_wallet_address.index');

            return new RedirectResponse($url);

        } catch (\Exception $e) {

            // TODO: build message
            //$message = $this->trans('address.flash.create_address_fail') . '. ' . $e->getMessage();
            //$messages[] = $message;

            throw $e;
        }

        $template = $this->getBaseTemplatePrefix() . ':Address:show_new.html';

        return $this->templating->renderResponse(
            $template . '.' . $this->getEngine(),
            array(
                // TODO: move to base controller and merge arrays
                'is_home' => false,
                'user' => array('is_authenticated' => true),
                'messages' => array(),
                //
                'coin_symbol' => 'btc',
                'address_form' => $createAddressForm->createView(),
            )
        );
    }
}