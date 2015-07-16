<?php

namespace BlockCypher\AppCommon\Infrastructure\AppCommonBundle\Security\Authentication\Provider;

use BlockCypher\AppCommon\App\Service\Internal\BlockCypherAuthenticationService;
use BlockCypher\AppCommon\Domain\User\User;
use BlockCypher\AppCommon\Infrastructure\AppCommonBundle\Security\BlockCypherUserToken;
use BlockCypher\Validation\TokenValidator;
use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * Class BlockCypherProvider
 * @package BlockCypher\AppCommon\Infrastructure\AppCommonBundle\Security\Authentication\Provider
 */
class BlockCypherProvider implements AuthenticationProviderInterface
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
        $blockCypherToken = null;

        /** @var User $user */
        $user = $this->userProvider->loadUserByUsername($token->getUsername());

        if ($user) {
            $blockCypherToken = $user->getBlockCypherToken();
        }

        if ($user && $this->validateBlockCypherToken($blockCypherToken)) {

            $authenticatedToken = new BlockCypherUserToken($user->getRoles());
            $authenticatedToken->setUser($user);

            return $authenticatedToken;
        }

        throw new AuthenticationException('BlockCypher token empty or invalid.');
    }

    /**
     * This function is specific to BlockCypher authentication and is only used to help this example
     *
     * For more information specific to the logic here, see
     * https://github.com/symfony/symfony-docs/pull/3134#issuecomment-27699129
     *
     * @param string $token
     * @return bool
     */
    protected function validateBlockCypherToken($token)
    {
        if (!TokenValidator::validate($token)) {
            return false;
        }

        $isTokenAuthenticated = $this->blockCypherAuthenticationService->authenticate($token);

        return $isTokenAuthenticated;
    }

    public function supports(TokenInterface $token)
    {
        return $token instanceof BlockCypherUserToken;
    }
}