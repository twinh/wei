<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget;

/**
 * A jQuery Validation style validator widget
 *
 * @author      Twin Huang <twinhuang@qq.com>
 * @link        http://bassistance.de/jquery-plugins/jquery-plugin-validation/
 * @property    \Widget\Is $is The validator manager
 */
class Validate extends AbstractWidget
{
    /**
     * The last validator object
     *
     * @var \Widget\Validator
     */
    protected $validator;
    
    /**
     * Create a new validator and validate by specified options
     * 
     * @param array $options
     * @return \Widget\Validator
     */
    public function __invoke(array $options = array())
    {
        $validator = $this->validator = $this->is->createValidator($options);
        
        $validator($options);
        
        return $validator;
    }
}
