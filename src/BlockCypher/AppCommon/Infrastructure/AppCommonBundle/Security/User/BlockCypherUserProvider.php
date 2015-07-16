<?php

namespace BlockCypher\AppCommon\Infrastructure\AppCommonBundle\Security\User;

use BlockCypher\AppCommon\Domain\User\User;
use BlockCypher\AppCommon\Domain\User\UserId;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * Class BlockCypherUserProvider
 * @package BlockCypher\AppCommon\Infrastructure\AppCommonBundle\Security\User
 */
class BlockCypherUserProvider implements UserProviderInterface
{
    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(
                sprintf('Instances of "%s" are not supported.', get_class($user))
            );
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    public function loadUserByUsername($username)
    {
        // make a call to UserRepository if registration is enabled in the future
        //$user = $this->userRepository->loadUserByUsername($username);

        $blockCypherToken = $username;

        return new User(new UserId($blockCypherToken), $blockCypherToken);

//        throw new UsernameNotFoundException(
//            sprintf('Username "%s" does not exist.', $username)
//        );
    }

    public function supportsClass($class)
    {
        return $class === 'BlockCypher\AppCommon\Domain\User\User';
    }
}