<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget\Validator\Rule;


/**
 * Check if the data ends with the specified string
 *
 * @package     Widget
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class EndsWith extends AbstractRule
{
    protected $findMe;
    
    protected $case = false;
    
    /**
     * @param boolean $case
     */
    public function __invoke($data, $options = array())
    {
        $this->option($options);
        
        $pos = strlen($data) - strlen($this->findMe);

        $fn = $this->case ? 'strrpos' : 'strripos';

        return $pos === $fn($data, $this->findMe);
    }
}
