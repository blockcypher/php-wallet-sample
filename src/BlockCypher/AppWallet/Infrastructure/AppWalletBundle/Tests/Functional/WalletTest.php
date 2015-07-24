<?php

namespace BlockCypher\AppWallet\Infrastructure\AppWalletBundle\Tests\Functional;

use BlockCypher\Api\Wallet as BlockCypherWallet;
use BlockCypher\AppCommon\Domain\User\UserId;
use BlockCypher\AppWallet\Domain\Wallet\Wallet;
use BlockCypher\AppWallet\Domain\Wallet\WalletCoinSymbol;
use BlockCypher\AppWallet\Domain\Wallet\WalletId;
use BlockCypher\Exception\BlockCypherConnectionException;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class WalletIndexTest
 * @package BlockCypher\AppWallet\Infrastructure\AppWalletBundle\Tests\Functional
 */
class WalletTest extends WebTestCase
{
    // Routes.
    // Hardcoding the request URLs is a best practice for functional tests.
    // If the test generates URLs using the Symfony router, it won't detect any change made to the application URLs
    // which may impact the end users.
    const ROUTE_WALLET_INDEX = '/wallets';

    /**
     * @var Wallet
     */
    protected $wallet;

    /**
     * @var BlockCypherWallet
     */
    protected $blockCypherWallet;

    public function setUp()
    {
        $this->loadWalletData(self::LOGGED_IN_USER_BC_TOKEN);

        $this->clientAuthenticated = static::createClient();

        $this->loginIn($this->clientAuthenticated, self::LOGGED_IN_USER_BC_TOKEN);

        $this->clientUnauthenticated = static::createClient();
    }

    /**
     * @param string $token
     * @throws BlockCypherConnectionException
     * @throws \Exception
     */
    private function loadWalletData($token)
    {
        $clock = $this->getContainer()->get('bc_app_common_infrastructure_app_common.clock');
        $walletRepository = $this->getContainer()->get('bc_app_wallet_wallet.wallet_repository');
        $blockCypherWalletService = $this->getContainer()->get('bc_app_common_blockcypher.wallet_service');

        $walletId = '5564B09652AFA054401239';

        // Create local Wallet
        $wallet = new Wallet(
            new WalletId($walletId),
            new UserId(new UserId($token), $token),
            $walletId,
            WalletCoinSymbol::BTC_TESTNET,
            $token,
            $clock->now()
        );
        $walletRepository->insert($wallet);

        // Create BlockCypher Wallet
        try {
            $blockCypherWallet = $blockCypherWalletService->createWallet(
                $wallet->getId()->getValue(),
                $wallet->getCoinSymbol(),
                $wallet->getToken()
            );
        } catch (BlockCypherConnectionException $e) {
            if ($e->getCode() != 409) {
                throw $e;
            } else {
                // Wallet already exists.
                $blockCypherWallet = $blockCypherWalletService->getWallet(
                    $wallet->getId()->getValue(),
                    $wallet->getCoinSymbol(),
                    $wallet->getToken()
                );
            }
        }

        $this->wallet = $wallet;
        $this->blockCypherWallet = $blockCypherWallet;
    }

    public function setDown()
    {
        // TODO: remove BlockCypher wallet
    }

    /**
     * @test
     */
    public function
    should_list_all_wallets_filter_by_the_logged_in_user()
    {
        $crawler = $this->clientAuthenticated->request('GET', self::ROUTE_WALLET_INDEX);
        $response = $this->clientAuthenticated->getResponse();

        // DEBUG
        //var_export($response->getContent());
        //die();

        $this->assertTrue($response->isSuccessful());
        $this->assertWalletListIsShown($crawler);
    }

    /**
     * @param Crawler $crawler
     */
    private function assertWalletListIsShown($crawler)
    {
        $walletId = $this->wallet->getId()->getValue();
        $this->assertTrue($crawler->filter('html:contains("' . $walletId . '")')->count() > 0);
    }

    /**
     * @test
     */
    public function
    should_redirect_user_to_login_when_he_tries_to_list_all_transactions_and_is_not_logged_in()
    {
        $this->clientUnauthenticated->request('GET', self::ROUTE_WALLET_INDEX);
        $response = $this->clientUnauthenticated->getResponse();

        $this->assertRedirectionToLogin($response);
    }
}