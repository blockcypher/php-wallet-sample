<?php

namespace BlockCypher\AppWallet\Infrastructure\AppWalletBundle\Controller;

use BlockCypher\AppCommon\Domain\User\User;
use BlockCypher\AppCommon\Domain\User\UserId;
use BlockCypher\AppCommon\Infrastructure\Controller\AppCommonController;
use BlockCypher\AppWallet\App\Command\CreateAddressCommand;
use BlockCypher\AppWallet\App\Command\CreateTransactionCommand;
use BlockCypher\AppWallet\App\Command\CreateWalletCommand;
use BlockCypher\AppWallet\Presentation\Facade\Dto\WalletDto;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class AppWalletController
 * @package BlockCypher\AppWallet\Infrastructure\AppWalletBundle\Controller
 */
class AppWalletController extends AppCommonController
{
    /**
     * @return string
     */
    public function getBaseTemplatePrefix()
    {
        return 'BlockCypherAppWalletInfrastructureAppWalletBundle';
    }

    /**
     * @return User|null
     */
    public function getUser()
    {
        $token = $this->tokenStorage->getToken();

        if ($token === null) {
            return;
        }

        $user = $token->getUser();

        if (!is_object($user)) {
            // e.g. anonymous authentication
            return;
        }

        return $user;
    }

    /**
     * @param string $walletId
     * @param string $tag
     * @param string $callbackUrl
     * @return CreateAddressCommand
     */
    protected function createCreateAddressCommand($walletId, $tag = '', $callbackUrl = '')
    {
        $createAddressCommand = new CreateAddressCommand($walletId, $tag, $callbackUrl);
        return $createAddressCommand;
    }

    /**
     * @param string $walletId
     * @param $payToAddress
     * @param $description
     * @param $amount
     * @return CreateTransactionCommand
     */
    protected function createCreateTransactionCommand($walletId, $payToAddress = '', $description = '', $amount = '')
    {
        $createTransactionCommand = new CreateTransactionCommand($walletId, $payToAddress, $description, $amount);
        return $createTransactionCommand;
    }

    /**
     * @param string $name
     * @param string $coinSymbol
     * @param string $walletOwnerId
     * @param string $token
     * @return CreateWalletCommand
     */
    protected function createCreateWalletCommand($name = '', $coinSymbol = '', $walletOwnerId = '', $token = '')
    {
        $createWalletCommand = new CreateWalletCommand($name, $coinSymbol, $walletOwnerId, $token);
        return $createWalletCommand;
    }

    /**
     * Shortcut to trans. Consider to put it in some common parent controller.
     * @param $id
     * @param array $parameters
     * @param string $domain
     * @param null $locale
     * @return string
     */
    protected function trans(
        $id,
        $parameters = array(),
        $domain = 'BlockCypherAppWalletInfrastructureAppWalletBundle',
        $locale = null
    )
    {
        return $this->translator->trans($id, $parameters, $domain, $locale);
    }

    /**
     * @param Request $request
     * @return array
     */
    protected function getBasicTemplateVariables(Request $request)
    {
        $coinSymbol = $request->get('CoinSymbol');
        if ($coinSymbol === null) {
            $coinSymbol = 'btc';
        }

        $userDto = array();
        $userDto['is_authenticated'] = false;

        $token = $this->tokenStorage->getToken();
        if ($token !== null && $token->getUser() !== null) {
            $userDto['is_authenticated'] = true;
        }

        // DEBUG
        //var_dump($userDto);
        //die();

        return array(
            'is_home' => false,
            'user' => $userDto,
            'messages' => $this->getMessageBag(),
            'coin_symbol' => $coinSymbol,
        );
    }

    /**
     * @param WalletDto $walletDto
     */
    protected function checkAuthorizationForWallet(WalletDto $walletDto)
    {
        $token = $this->tokenStorage->getToken();

        if (!$token) {
            throw $this->createAccessDeniedException();
        }

        /** @var User $user */
        $user = $token->getUser();

        if (!$user) {
            throw $this->createAccessDeniedException();
        }

        // Is the user the owner of this wallet?
        if (!$user->getId()->equals(new UserId($walletDto->getUserId()))) {
            throw $this->createAccessDeniedException();
        }
    }
}