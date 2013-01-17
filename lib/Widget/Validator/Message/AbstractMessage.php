<?php
/**
 * Widget Framework
 *
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget\Validator\Message;

/**
 * The base class for validate message
 *
 * @author Twin
 */
abstract class AbstractMessage implements MessageInterface
{
    protected $messages = array();

    /**
     * {@inheritdoc}
     */
    public function getMessage($name)
    {
        return isset($this->messages[$name]) ? $this->messages[$name] : false;
    }

    /**
     * {@inheritdoc}
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * {@inheritdoc}
     */
    public function setMessage($name, $data)
    {
        $this->messages[$name] = $data;
        
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setMessages($messages)
    {
        $this->messages = $messages;
        
        return $this;
    }
}
