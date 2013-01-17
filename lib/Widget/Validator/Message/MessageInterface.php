<?php
/**
 * Widget Framework
 *
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget\Validator\Message;

/**
 * The validate message interface
 *
 * @package     Widget
 * @author      Twin Huang <twinh@yahoo.cn>
 */
interface MessageInterface
{
    /**
     * Returns all messages
     */
    public function getMessages();
    
    /**
     * Sets all messages
     */
    public function setMessages($messages);
    
    /**
     * Returns specify message by given rule name
     */
    public function getMessage($name);
    
    /**
     * Sets specify message by given rule name
     */
    public function setMessage($name, $data);
}
