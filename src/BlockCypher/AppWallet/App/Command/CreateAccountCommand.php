<?php

namespace BlockCypher\AppWallet\App\Command;

use SimpleBus\Message\Name\NamedMessage;

/**
 * Class CreateAccountCommand
 * @package BlockCypher\AppWallet\App\Command
 */
class CreateAccountCommand implements NamedMessage
{
    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $tag;

    /**
     * @param $type
     * @param string $tag
     */
    public function __construct($type, $tag)
    {
        $this->type = $type;
        $this->tag = $tag;
    }

    /**
     * The name of this particular type of message.
     *
     * @return string
     */
    public static function messageName()
    {
        return 'bc_app_wallet_account_create_account';
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return string
     */
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * @param string $tag
     * @return $this
     */
    public function setTag($tag)
    {
        $this->tag = $tag;
        return $this;
    }
}