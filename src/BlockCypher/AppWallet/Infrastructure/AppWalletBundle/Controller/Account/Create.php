<?php

namespace BlockCypher\AppWallet\Infrastructure\AppWalletBundle\Controller\Account;

use BlockCypher\AppWallet\App\Command\CreateAccountCommand;
use BlockCypher\AppWallet\Infrastructure\AppWalletBundle\Controller\AppWalletController;
use BlockCypher\AppWallet\Infrastructure\AppWalletBundle\Form\Account\AccountFormFactory;
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
     * @var AccountFormFactory
     */
    private $accountFormFactory;

    /**
     * @var MessageBus
     */
    private $commandBus;

    /**
     * @param EngineInterface $templating
     * @param TranslatorInterface $translator
     * @param RouterInterface $router
     * @param AccountFormFactory $accountFormFactory
     * @param MessageBus $commandBus
     */
    public function __construct(
        EngineInterface $templating,
        TranslatorInterface $translator,
        RouterInterface $router,
        AccountFormFactory $accountFormFactory,
        MessageBus $commandBus)
    {
        parent::__construct($templating, $translator);
        $this->router = $router;
        $this->accountFormFactory = $accountFormFactory;
        $this->commandBus = $commandBus;
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function __invoke(Request $request)
    {
        $createAccountCommand = $this->createCreateAccountCommand();

        $createAccountForm = $this->accountFormFactory->createCreateForm($createAccountCommand);

        $createAccountForm->handleRequest($request);

        $messages = array();

        if (!$createAccountForm->isValid()) {

            $message = $this->trans('account_form.invalid_fields');
            $errors = $createAccountForm->getErrors();
            foreach ($errors as $error) {
                $message .= $error->getMessage();
            }
            $messages[] = $message;

            // TODO: render messages in template
            throw new \Exception(print_r($messages, true));

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

        /** @var CreateAccountCommand $createAccountCommand */
        $createAccountCommand = $createAccountForm->getData();

        try {

            $this->commandBus->handle($createAccountCommand);

            $message = $this->trans('account.flash.create_successfully');

            $url = $this->router->generate('bc_app_wallet_account.index');

            return new RedirectResponse($url);

        } catch (\Exception $e) {

            // TODO: build message
            //$message = $this->trans('account.flash.create_account_fail') . '. ' . $e->getMessage();
            //$messages[] = $message;

            throw $e;
        }

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