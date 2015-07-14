<?php

namespace BlockCypher\AppWallet\Infrastructure\AppWalletBundle\Controller\Transaction;

use BlockCypher\AppWallet\Infrastructure\AppWalletBundle\Controller\AppWalletController;
use BlockCypher\AppWallet\Presentation\Facade\WalletServiceFacade;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class Index
 * @package BlockCypher\AppWallet\Infrastructure\AppWalletBundle\Controller\Transaction
 */
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
        WalletServiceFacade $walletServiceFacade
    )
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
        $walletId = $request->get('walletId');

        $walletDto = $this->walletServiceFacade->getWallet($walletId);
        $walletTransactionDto = $this->walletServiceFacade->listWalletTransactions($walletId);
        $transactions = $walletTransactionDto->getTransactionListItemDtos();

        $template = $this->getBaseTemplatePrefix() . ':Transaction:index.html';

        // TODO: paging
        $currentPage = 1;
        $maxPages = 0; // get_max_pages(num_items=address_details['final_n_tx'], items_per_page=TXNS_PER_PAGE),

        return $this->templating->renderResponse(
            $template . '.' . $this->getEngine(),
            array_merge($this->getBasicTemplateVariables($request),
                array(
                    'current_page' => $currentPage,
                    'max_pages' => $maxPages,
                    'wallet' => $walletDto,
                    'num_all_txns' => $walletTransactionDto->getNTx(),
                    'num_unconfirmed_txns' => $walletTransactionDto->getUnconfirmedNTx(),
                    'all_transactions' => $transactions,
                )
            )
        );
    }
}