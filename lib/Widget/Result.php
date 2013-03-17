<?php
/**
 * Widget Framework
 *
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget;

/**
 * Result
 *
 * @author      Twin Huang <twinh@yahoo.cn>
 * @todo        xml, others?
 * @todo        as response ?
 */
class Result extends AbstractWidget
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
    
    public function __invoke($message, $code = 0, array $append = array())
    {
        return json_encode(array(
            $this->code => $code,
            $this->message => $message,
        ) + $append);
    }
}
