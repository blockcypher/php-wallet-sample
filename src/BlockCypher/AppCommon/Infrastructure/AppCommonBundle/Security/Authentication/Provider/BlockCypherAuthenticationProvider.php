<?php

namespace BlockCypher\AppCommon\Infrastructure\AppCommonBundle\Security\Authentication\Provider;

use BlockCypher\AppCommon\App\Service\Internal\BlockCypherAuthenticationService;
use BlockCypher\AppCommon\Domain\User\User;
use BlockCypher\AppCommon\Infrastructure\AppCommonBundle\Security\BlockCypherUserToken;
use BlockCypher\Validation\TokenValidator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;

/**
 * Class BlockCypherAuthenticationProvider
 * @package BlockCypher\AppCommon\Infrastructure\AppCommonBundle\Security\Authentication\Provider
 */
class BlockCypherAuthenticationProvider implements AuthenticationProviderInterface, AuthenticationFailureHandlerInterface
{
    /**
     * @var UserProviderInterface
     */
    private $userProvider;

    /**
     * @var BlockCypherAuthenticationService
     */
    private $blockCypherAuthenticationService;

    /**
     * @param UserProviderInterface $userProvider
     * @param BlockCypherAuthenticationService $blockCypherAuthenticationService
     */
    public function __construct(
        UserProviderInterface $userProvider,
        BlockCypherAuthenticationService $blockCypherAuthenticationService
    )
    {
        $this->userProvider = $userProvider;
        $this->blockCypherAuthenticationService = $blockCypherAuthenticationService;

    }

    /**
     * @param TokenInterface $token
     * @return BlockCypherUserToken
     */
    public function authenticate(TokenInterface $token)
    {
//        if (!$userProvider instanceof ApiKeyUserProvider) {
//            throw new \InvalidArgumentException(
//                sprintf(
//                    'The user provider must be an instance of ApiKeyUserProvider (%s was given).',
//                    get_class($userProvider)
//                )
//            );
//        }

        //$blockCypherToken = null;

        $blockCypherToken = $token->getCredentials();
        $username = $blockCypherToken;

        // DEBUG
        //var_dump($blockCypherToken);
        //die();

        /** @var User $user */
        $user = $this->userProvider->loadUserByUsername($username);

        // DEBUG
        //var_dump($user);
        //die();

        if ($user) {
            $blockCypherToken = $user->getBlockCypherToken();
        }

        if ($user && $this->validateBlockCypherToken($blockCypherToken)) {

            $authenticatedToken = new BlockCypherUserToken(
                $user,
                $blockCypherToken,
                'blockcypher',
                $user->getRoles()
            );
            //$authenticatedToken->setUser($user);

            return $authenticatedToken;
        }

        throw new AuthenticationException('BlockCypher token empty or invalid.');
    }

    /**
     * @param string $token
     * @return bool
     */
    private function validateBlockCypherToken($token)
    {
        if (!TokenValidator::validate($token)) {
            return false;
        }

        $isTokenAuthenticated = $this->blockCypherAuthenticationService->authenticate($token);

        return $isTokenAuthenticated;
    }

    /**
     * This is called when an interactive authentication attempt fails. This is
     * called by authentication listeners inheriting from
     * AbstractAuthenticationListener.
     *
     * @param Request $request
     * @param AuthenticationException $exception
     *
     * @return Response The response to return, never null
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        return new Response("Authentication Failed.", 403);
    }

    public function supports(TokenInterface $token)
    {
        return $token instanceof BlockCypherUserToken;
    }
}