<?php

namespace BlockCypher\AppCommon\Domain\User;

use BlockCypher\AppCommon\Domain\Model;
use Symfony\Component\Security\Core\Role\Role;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class User
 * @package BlockCypher\AppCommon\Domain\User
 */
class User extends Model implements UserInterface, EquatableInterface
{
    /**
     * @var UserId
     */
    private $id;

    /**
     * @var string
     */
    private $blockCypherToken;

    /**
     * @param UserId $id
     * @param string $blockCypherToken
     */
    function __construct(UserId $id, $blockCypherToken)
    {
        $this->id = $id;
        $this->blockCypherToken = $blockCypherToken;
    }

    /**
     * Returns the roles granted to the user.
     *
     * <code>
     * public function getRoles()
     * {
     *     return array('ROLE_USER');
     * }
     * </code>
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return Role[] The user roles
     */
    public function getRoles()
    {
        return array('ROLE_USER');
    }

    /**
     * Returns the password used to authenticate the user.
     *
     * This should be the encoded password. On authentication, a plain-text
     * password will be salted, encoded, and then compared to this value.
     *
     * @return string The password
     */
    public function getPassword()
    {
        // TODO: Implement getPassword() method.
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    /**
     * @return UserId
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * The equality comparison should neither be done by referential equality
     * nor by comparing identities (i.e. getId() === getId()).
     *
     * However, you do not need to compare every attribute, but only those that
     * are relevant for assessing whether re-authentication is required.
     *
     * Also implementation should consider that $user instance may implement
     * the extended user interface `AdvancedUserInterface`.
     *
     * @param UserInterface $user
     *
     * @return bool
     */
    public function isEqualTo(UserInterface $user)
    {
        if (!$user instanceof User) {
            return false;
        }

        if ($this->blockCypherToken !== $user->getBlockCypherToken()) {
            return false;
        }

//        if ($this->salt !== $user->getSalt()) {
//            return false;
//        }
//
//        if ($this->username !== $user->getUsername()) {
//            return false;
//        }

        return true;
    }

    /**
     * @return string
     */
    public function getBlockCypherToken()
    {
        return $this->blockCypherToken;
    }

    public function __toString()
    {
        return (string)$this->getUsername();
    }

    /**
     * Returns the username used to authenticate the user.
     *
     * @return string The username
     */
    public function getUsername()
    {
        // For the time being users are not registered in the app and we use BlockCypher token as username.
        return $this->blockCypherToken;
    }
}