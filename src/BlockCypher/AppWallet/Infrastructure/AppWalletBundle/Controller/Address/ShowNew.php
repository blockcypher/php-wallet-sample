<?php

namespace BlockCypher\AppWallet\Infrastructure\AppWalletBundle\Controller\Address;

use BlockCypher\AppWallet\Infrastructure\AppWalletBundle\Controller\AppWalletController;
use BlockCypher\AppWallet\Infrastructure\AppWalletBundle\Form\AddressFormFactory;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Translation\TranslatorInterface;

class ShowNew extends AppWalletController
{
    /**
     * @var AddressFormFactory
     */
    private $addressFormFactory;

    /**
     * @param EngineInterface $templating
     * @param TranslatorInterface $translator
     * @param AddressFormFactory $addressFormFactory
     */
    public function __construct(
        EngineInterface $templating,
        TranslatorInterface $translator,
        AddressFormFactory $addressFormFactory)
    {
        parent::__construct($templating, $translator);
        $this->addressFormFactory = $addressFormFactory;
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function __invoke(Request $request)
    {
        $accountId = $request->get('account_id');
        if ($accountId === null) {
            // TODO: get user´s default/primary account
            $accountId = '1A311E0C-B6A6-4679-9F7B-21FDB265E135';
        }

        $tag = '';
        $callbackUrl = '';
        $createAddressCommand = $this->createCreateAddressCommand($accountId, $tag, $callbackUrl);

        $createAddressForm = $this->addressFormFactory->createCreateForm($createAddressCommand);

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