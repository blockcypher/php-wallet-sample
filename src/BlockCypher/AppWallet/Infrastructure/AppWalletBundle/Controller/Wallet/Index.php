<?php

namespace BlockCypher\AppWallet\Infrastructure\AppWalletBundle\Controller\Wallet;

use BlockCypher\AppWallet\Infrastructure\AppWalletBundle\Controller\AppWalletController;
use BlockCypher\AppWallet\Presentation\Facade\WalletServiceFacade;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Translation\TranslatorInterface;

class Index extends AppWalletController
{
    /**
     * @var WalletServiceFacade
     */
    private $walletServiceFacade;

    /**
     * @param EngineInterface $templating
     * @param TranslatorInterface $translator
     * @param Session $session
     * @param WalletServiceFacade $walletServiceFacade
     */
    public function __construct(
        EngineInterface $templating,
        TranslatorInterface $translator,
        Session $session,
        WalletServiceFacade $walletServiceFacade)
    {
        parent::__construct($templating, $translator, $session);
        $this->walletServiceFacade = $walletServiceFacade;
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function __invoke(Request $request)
    {
        $wallets = $this->walletServiceFacade->listWallets();

        $template = $this->getBaseTemplatePrefix() . ':Wallet:index.html';

        // DEBUG
        //var_dump($wallets);
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
                'num_all_wallets' => count($wallets),
                'max_pages' => $maxPages,
                'wallets' => $wallets
            )
        );
    }
}