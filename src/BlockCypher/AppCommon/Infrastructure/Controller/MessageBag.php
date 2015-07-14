<?php

namespace BlockCypher\AppCommon\Infrastructure\Controller;

/**
 * Class MessageBag
 * @package BlockCypher\AppCommon\Infrastructure\Controller
 */
class MessageBag
{
    /**
     * Messages.
     *
     * @var array
     */
    private $messages = array();

    /**
     * Constructor.
     */
    public function __construct()
    {
    }

    /**
     * @param $type
     * @param $message
     */
    public function add($type, $message)
    {
        $this->messages[$type][] = $message;
    }

    /**
     * @param $type
     * @param array $default
     * @return array
     */
    public function peek($type, array $default = array())
    {
        return $this->has($type) ? $this->messages[$type] : $default;
    }

    /**
     * @param $type
     * @return bool
     */
    public function has($type)
    {
        return array_key_exists($type, $this->messages) && $this->messages[$type];
    }

    /**
     * @param $type
     * @param array $default
     * @return array
     */
    public function get($type, array $default = array())
    {
        if (!$this->has($type)) {
            return $default;
        }

        $return = $this->messages[$type];

        unset($this->messages[$type]);

        return $return;
    }

    /**
     * @param $type
     * @param $messages
     */
    public function set($type, $messages)
    {
        $this->messages[$type] = (array)$messages;
    }

    /**
     * @param array $messages
     */
    public function setAll(array $messages)
    {
        $this->messages = $messages;
    }

    /**
     * @return array
     */
    public function keys()
    {
        return array_keys($this->messages);
    }

    /**
     * @return array
     */
    public function clear()
    {
        return $this->all();
    }

    /**
     * @return array
     */
    public function all()
    {
        $return = $this->peekAll();
        $this->messages = array();

        return $return;
    }

    /**
     * @return array
     */
    public function peekAll()
    {
        return $this->messages;
    }
}