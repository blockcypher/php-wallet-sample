<?php

namespace BlockCypher\AppWallet\Infrastructure\AppWalletBundle\Controller;

use BlockCypher\AppWallet\Presentation\Facade\AccountServiceFacade;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Request;

class AccountsController extends AppWalletController
{
    /**
     * @var AccountServiceFacade
     */
    private $accountServiceFacade;

    /**
     * @param EngineInterface $templating
     * @param AccountServiceFacade $accountServiceFacade
     */
    public function __construct(
        EngineInterface $templating,
        AccountServiceFacade $accountServiceFacade)
    {
        parent::__construct($templating);
        $this->accountServiceFacade = $accountServiceFacade;
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function __invoke(Request $request)
    {
        $BLOCKCYPHER_PUBLIC_KEY = '31c49f33f35c85a8f4d9845a754f7c8e';

        $coinSymbol = $request->get('coinSymbol');
        $token = $request->get('token');
        if (!$token) {
            //$this->createAccessDeniedException();
            $token = $BLOCKCYPHER_PUBLIC_KEY; // TODO: get from app parameters.yml
        }

        $accounts = $this->accountServiceFacade->listAccounts();

        $template = $this->getBaseTemplatePrefix() . ':Accounts:accounts.html';

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
                'coin_symbol' => $coinSymbol,
                'current_page' => $currentPage,
                'num_all_accounts' => count($accounts),
                'max_pages' => $maxPages,
                'accounts' => $accounts
            )
        );
    }
}