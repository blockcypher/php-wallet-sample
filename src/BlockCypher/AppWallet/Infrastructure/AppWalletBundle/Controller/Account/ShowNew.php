<?php

namespace BlockCypher\AppWallet\Infrastructure\AppWalletBundle\Controller\Account;

use BlockCypher\AppWallet\Infrastructure\AppWalletBundle\Controller\AppWalletController;
use BlockCypher\AppWallet\Infrastructure\AppWalletBundle\Form\Account\AccountFormFactory;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Translation\TranslatorInterface;

class ShowNew extends AppWalletController
{
    /**
     * @var AccountFormFactory
     */
    private $accountFormFactory;

    /**
     * @param EngineInterface $templating
     * @param TranslatorInterface $translator
     * @param AccountFormFactory $accountFormFactory
     */
    public function __construct(
        EngineInterface $templating,
        TranslatorInterface $translator,
        AccountFormFactory $accountFormFactory)
    {
        parent::__construct($templating, $translator);
        $this->accountFormFactory = $accountFormFactory;
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function __invoke(Request $request)
    {
        $accountId = $request->get('accountId');
        if ($accountId === null) {
            // TODO: get user´s default/primary account
            $accountId = '1A311E0C-B6A6-4679-9F7B-21FDB265E135';
        }

        $tag = '';
        $callbackUrl = '';
        $createAccountCommand = $this->createCreateAccountCommand($accountId, $tag, $callbackUrl);

        $createAccountForm = $this->accountFormFactory->createCreateForm($createAccountCommand);

        $template = $this->getBaseTemplatePrefix() . ':Account:show_new.html';

        return $this->templating->renderResponse(
            $template . '.' . $this->getEngine(),
            array(
                // TODO: move to base controller and merge arrays
                'is_home' => false,
                'user' => array('is_authenticated' => true),
                'messages' => array(),
                //
                'coin_symbol' => 'btc',
                'account_form' => $createAccountForm->createView(),
            )
        );
    }
}