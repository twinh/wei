<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget\Validator;

/**
 * Check if the input value is valid by all of the rules
 * 
 * @author      Twin Huang <twinh@yahoo.cn>
 * @property \Widget\Is $is The validator manager
 */
class AllOf extends SomeOf
{
    protected $atLeastMessage = '%name% must be passed by all of these rules';
    
    public function __invoke($input, array $rules = array(), $atLeast = null)
    {
        $this->atLeast = count($rules ?: $this->rules);
        
        return parent::__invoke($input, $rules, $atLeast);
    }
}
