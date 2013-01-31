<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget\Validator;

/**
 * @package     Widget
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class StartsWith extends AbstractRule
{
    protected $findMe;
    
    protected $case = false;
    
    protected $message = 'This value must start with: {{ findMe }}';
    
    /**
     * @param boolean $case
     */
    public function __invoke($data, $options = array())
    {
        $this->option($options);

        $fn = $this->case ? 'strpos' : 'stripos';

        return 0 === $fn($data, $this->findMe);
    }
}
