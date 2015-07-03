<?php

namespace BlockCypher\AppExplorer\Infrastructure\AppExplorerBundle\Controller;

use BlockCypher\AppExplorer\Presentation\Facade\BlockServiceFacade;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Translation\TranslatorInterface;

class BlockOverviewController extends AppExplorerController
{
    /**
     * @var BlockServiceFacade
     */
    private $blockServiceFacade;

    /**
     * @param EngineInterface $templating
     * @param TranslatorInterface $translator
     * @param BlockServiceFacade $blockServiceFacade
     */
    public function __construct(
        EngineInterface $templating,
        TranslatorInterface $translator,
        BlockServiceFacade $blockServiceFacade
    )
    {
        parent::__construct($templating, $translator);
        $this->blockServiceFacade = $blockServiceFacade;
    }

    /**
     * @param Request $request
     * @param string $hashOrHeight
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function __invoke(Request $request, $hashOrHeight)
    {
        // Port from: https://github.com/blockcypher/explorer/blob/master/blocks/views.py#L19

        $BLOCKCYPHER_PUBLIC_KEY = 'c0afcccdde5081d6429de37d16166ead';

        $token = $request->get('token');
        if (!$token) {
            //$this->createAccessDeniedException();
            $token = $BLOCKCYPHER_PUBLIC_KEY; // TODO: get from app parameters.yml
        }

        // TODO: if not valid address redirect to coinSymbol overview

        $coinSymbol = $request->get('coinSymbol');

        // Transactions pagination
        $params = array(
            'instart' => 1,
            'outstart' => 1,
            'limit' => 1,
        );

        $blockDetailsArray = $this->blockServiceFacade->getBlockDetails($hashOrHeight, $params, $coinSymbol, $token);

        // TODO: It seems Python version does some kind of short with transactions.
        // https://github.com/blockcypher/blockcypher-python/blob/e5dfd5fb1065fb54f8464f2c04279dc90aed86d1/blockcypher/api.py#L652

        $template = $this->getBaseTemplatePrefix() . ':BlockOverview:block_overview.html';

        // TODO
        $currentPage = 1;
        $maxPages = 0; // get_max_pages(num_items=block_details['n_tx'], items_per_page=TXNS_PER_PAGE),
        $apiUrl = "https://api.blockcypher.com/v1/{$coinSymbol}/block/{$hashOrHeight}/"; // TODO: get base url from php-client const?

        return $this->templating->renderResponse(
            $template . '.' . $this->getEngine(),
            array(
                // TODO: move to base controller and merge arrays
                'is_home' => false,
                'user' => array('is_authenticated' => true),
                'messages' => array(),
                //
                'coin_symbol' => $coinSymbol,
                'api_url' => $apiUrl,
                'block_details' => $blockDetailsArray,
                'current_page' => $currentPage,
                'max_pages' => $maxPages,
            )
        );
    }
}