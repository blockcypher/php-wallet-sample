<?php

namespace BlockCypher\AppWallet\Infrastructure\AppWalletBundle\Controller\Address;

use BlockCypher\AppWallet\Infrastructure\AppWalletBundle\Controller\AppWalletController;
use BlockCypher\AppWallet\Presentation\Facade\AddressServiceFacade;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Translation\TranslatorInterface;

class Index extends AppWalletController
{
    /**
     * @var AddressServiceFacade
     */
    private $addressServiceFacade;

    /**
     * @param EngineInterface $templating
     * @param TranslatorInterface $translator
     * @param AddressServiceFacade $addressServiceFacade
     */
    public function __construct(
        EngineInterface $templating,
        TranslatorInterface $translator,
        AddressServiceFacade $addressServiceFacade)
    {
        parent::__construct($templating, $translator);
        $this->addressServiceFacade = $addressServiceFacade;
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function __invoke(Request $request)
    {
        $accountId = $request->get('accountId');
        if ($accountId === null) {
            // If not account_id specified then use primary/default account
            $primaryAccount = "1A311E0C-B6A6-4679-9F7B-21FDB265E135"; // TODO: get from user profile or account field
            $accountId = $primaryAccount;
        }

        $addresses = $this->addressServiceFacade->listAccountAddresses($accountId);

        // DEBUG
        //var_dump($addresses);
        //die();

        $template = $this->getBaseTemplatePrefix() . ':Address:index.html';

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
                'max_pages' => $maxPages,
                'account_id' => $accountId,
                'num_all_addresses' => count($addresses),
                'addresses' => $addresses
            )
        );
    }
}