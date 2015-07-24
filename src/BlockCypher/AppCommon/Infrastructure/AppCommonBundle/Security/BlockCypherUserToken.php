<?php

namespace BlockCypher\AppCommon\Infrastructure\AppCommonBundle\Security;

use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;
use Symfony\Component\Security\Core\Role\RoleInterface;

/**
 * Class BlockCypherUserToken
 * @package BlockCypher\AppCommon\Infrastructure\AppCommonBundle\Security
 */
class BlockCypherUserToken extends AbstractToken
{
    /**
     * @var string
     */
    private $credentials;

    /**
     * @var string
     */
    private $providerKey;

    /**
     * Constructor.
     *
     * @param string|object $user The username (like a nickname, email address, etc.), or a UserInterface instance or an object implementing a __toString method.
     * @param string $credentials This usually is the password of the user
     * @param string $providerKey The provider key
     * @param RoleInterface[]|string[] $roles An array of roles
     *
     * @throws \InvalidArgumentException
     */
    public function __construct($user, $credentials, $providerKey, array $roles = array())
    {
        parent::__construct($roles);

        if (empty($providerKey)) {
            throw new \InvalidArgumentException('$providerKey must not be empty.');
        }

        $this->setUser($user);
        $this->credentials = $credentials;
        $this->providerKey = $providerKey;

        parent::setAuthenticated(count($roles) > 0);
    }

    /**
     * {@inheritdoc}
     */
    public function setAuthenticated($isAuthenticated)
    {
        if ($isAuthenticated) {
            throw new \LogicException('Cannot set this token to trusted after instantiation.');
        }

        parent::setAuthenticated(false);
    }

    /**
     * {@inheritdoc}
     */
    public function getCredentials()
    {
        return $this->credentials;
    }

    /**
     * Returns the provider key.
     *
     * @return string The provider key
     */
    public function getProviderKey()
    {
        return $this->providerKey;
    }

    /**
     * {@inheritdoc}
     */
    public function eraseCredentials()
    {
        parent::eraseCredentials();

        $this->credentials = null;
    }

    /**
     * {@inheritdoc}
     */
    public function serialize()
    {
        return serialize(array($this->credentials, $this->providerKey, parent::serialize()));
    }

    /**
     * {@inheritdoc}
     */
    public function unserialize($serialized)
    {
        list($this->credentials, $this->providerKey, $parentStr) = unserialize($serialized);
        parent::unserialize($parentStr);
    }
}