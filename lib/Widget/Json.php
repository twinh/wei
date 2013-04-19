<?php
/**
 * Widget Library
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget;

/**
 * A widget to response json
 *
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class Json extends Response
{
    /**
     * The key name of code
     * 
     * @var string
     */
    protected $code = 'code';
    
    /**
     * The key name of message
     * 
     * @var string
     */
    protected $message = 'message';
    
    public function __invoke($message, $code = 0, array $append = array(), $callback = false)
    {
        $result = json_encode(array(
            $this->code => $code,
            $this->message => $message,
        ) + $append);
        
        if ($callback) {
            $callback = $this->escape->js($this->request['callback']);
            $result = $callback . '(' . $result . ')';
        }
        
        return parent::send($result);
    }
}