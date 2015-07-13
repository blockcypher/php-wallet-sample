<?php

namespace BlockCypher\AppCommon\App\Service\Internal\Exception;

use DomainException;

class InvalidTransaction extends \DomainException
{
    /**
     * @param string[] $messages
     */
    public function __construct($messages)
    {
        // DEBUG
        //var_dump($messages);
        //die();

        parent::__construct(
            sprintf(
                'Invalid transaction. %s',
                implode(' ', $messages)
            )
        );
    }
}