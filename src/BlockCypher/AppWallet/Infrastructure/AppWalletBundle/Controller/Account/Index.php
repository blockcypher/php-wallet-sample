<?php

namespace BlockCypher\AppWallet\Infrastructure\AppWalletBundle\Controller\Account;

use BlockCypher\AppWallet\Infrastructure\AppWalletBundle\Controller\AppWalletController;
use BlockCypher\AppWallet\Presentation\Facade\AccountServiceFacade;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Translation\TranslatorInterface;

class Index extends AppWalletController
{
    /**
     * @var AccountServiceFacade
     */
    private $accountServiceFacade;

    /**
     * @param EngineInterface $templating
     * @param TranslatorInterface $translator
     * @param AccountServiceFacade $accountServiceFacade
     */
    public function __construct(
        EngineInterface $templating,
        TranslatorInterface $translator,
        AccountServiceFacade $accountServiceFacade)
    {
        parent::__construct($templating, $translator);
        $this->accountServiceFacade = $accountServiceFacade;
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function __invoke(Request $request)
    {
        $accounts = $this->accountServiceFacade->listAccounts();

        $template = $this->getBaseTemplatePrefix() . ':Accounts:index.html';

        // DEBUG
        //var_dump($accounts);
        //die();

        // TODO
        $currentPage = 1;
        $maxPages = 0; // get_max_pages(num_items=address_details['final_n_tx'], items_per_page=TXNS_PER_PAGE),

        return $this->templating->renderResponse(
            $template . '.' . $this->getEngine(),
            array(
                // TODO: move to base controller and merge arrays
                'is_home' => false,
                'user' => array('is_authenticated' => true),
                'messages' => array(),
                //
                'coin_symbol' => 'btc',
                'current_page' => $currentPage,
                'num_all_accounts' => count($accounts),
                'max_pages' => $maxPages,
                'accounts' => $accounts
            )
        );
    }
}