<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Widget;

/**
 * A jQuery Validation style validator widget
 *
 * @author      Twin Huang <twinhuang@qq.com>
 * @link        http://bassistance.de/jquery-plugins/jquery-plugin-validation/
 * @property    Is $is The validator manager
 */
class Validate extends AbstractWidget
{
    /**
     * The last validator object
     *
     * @var Validator
     */
    protected $validator;
    
    /**
     * Create a new validator and validate by specified options
     * 
     * @param array $options
     * @return Validator
     */
    public function __invoke(array $options = array())
    {
        $validator = $this->validator = $this->is->createValidator($options);
        
        $validator($options);
        
        return $validator;
    }
}
