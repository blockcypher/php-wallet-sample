<?php

namespace BlockCypher\AppExplorer\Infrastructure\AppExplorerBundle\Controller;

use BlockCypher\AppExplorer\Presentation\Facade\AddressServiceFacade;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Translation\TranslatorInterface;

class AddressOverviewController extends AppExplorerController
{
    /**
     * @param EngineInterface $templating
     * @param TranslatorInterface $translator
     * @param AddressServiceFacade $walletServiceFacade
     */
    public function __construct(
        EngineInterface $templating,
        TranslatorInterface $translator,
        AddressServiceFacade $walletServiceFacade
    )
    {
        parent::__construct($templating, $translator);
        $this->addressServiceFacade = $walletServiceFacade;
    }

    /**
     * @param Request $request
     * @param string $address
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function __invoke(Request $request, $address)
    {
        // Port from: https://github.com/blockcypher/explorer/blob/master/addresses/views.py#L43

        $BLOCKCYPHER_PUBLIC_KEY = 'c0afcccdde5081d6429de37d16166ead';

        $token = $request->get('token');
        if (!$token) {
            //$this->createAccessDeniedException();
            $token = $BLOCKCYPHER_PUBLIC_KEY; // TODO: get from app parameters.yml
        }

        // TODO: if not valid address redirect to coinSymbol overview

        $coinSymbol = $request->get('coinSymbol');

        $addressDetailsArray = $this->addressServiceFacade->getAddress($address, $coinSymbol, $token);
        $allTransactions = $addressDetailsArray['all_transactions'];

        $template = $this->getBaseTemplatePrefix() . ':AddressOverview:address_overview.html';

        // TODO
        $currentPage = 1;
        $maxPages = 0; // get_max_pages(num_items=address_details['final_n_tx'], items_per_page=TXNS_PER_PAGE),
        $walletName = null;
        $apiUrl = "https://api.blockcypher.com/v1/btc/main/addrs/{$address}/"; // TODO: get base url from php-client const?

        return $this->templating->renderResponse(
            $template . '.' . $this->getEngine(),
            array(
                // TODO: move to base controller and merge arrays
                'is_home' => false,
                'user' => array('is_authenticated' => true),
                'messages' => array(),
                //
                'coin_symbol' => $coinSymbol,
                'address' => $address,
                'api_url' => $apiUrl,
                'wallet_name' => $walletName,
                'current_page' => $currentPage,
                'max_pages' => $maxPages,
                'total_sent_satoshis' => $addressDetailsArray['total_sent'],
                'total_received_satoshis' => $addressDetailsArray['total_received'],
                'unconfirmed_balance_satoshis' => $addressDetailsArray['unconfirmed_balance'],
                'confirmed_balance_satoshis' => $addressDetailsArray['balance'],
                'total_balance_satoshis' => $addressDetailsArray['final_balance'],
                'all_transactions' => $allTransactions,
                'num_confirmed_txns' => $addressDetailsArray['n_tx'],
                'num_unconfirmed_txns' => $addressDetailsArray['unconfirmed_n_tx'],
                'num_all_txns' => $addressDetailsArray['final_n_tx'],
                'BLOCKCYPHER_PUBLIC_KEY' => $BLOCKCYPHER_PUBLIC_KEY,
            )
        );
    }
}