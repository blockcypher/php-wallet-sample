<?php

namespace BlockCypher\AppCommon\Infrastructure\AppCommonBundle\Security;

use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;

/**
 * Class BlockCypherUserToken
 * @package BlockCypher\AppCommon\Infrastructure\AppCommonBundle\Security
 */
class BlockCypherUserToken extends AbstractToken
{
    public function __construct(array $roles = array())
    {
        parent::__construct($roles);

        // If the user has roles, consider it authenticated
        $this->setAuthenticated(count($roles) > 0);
    }

    public function getCredentials()
    {
        return '';
    }
}