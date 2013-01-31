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
    
    public function __invoke($input, $findMe = null, $case = null)
    {
        $findMe && $this->findMe = $findMe;
        is_bool($case) && $this->case = $case;
        
        $fn = $this->case ? 'strpos' : 'stripos';

        return 0 === $fn($input, $this->findMe);
    }
}
