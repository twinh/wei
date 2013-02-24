<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget;

/**
 * Validate
 *
 * @package     Widget
 * @author      Twin Huang <twinh@yahoo.cn>
 * @link        http://bassistance.de/jquery-plugins/jquery-plugin-validation/
 * @property \Widget\Is $is The validator manager
 */
class Validate extends AbstractWidget
{
    /**
     * The last validator object
     *
     * @var \Widget\Validator
     */
    protected $validator;
    
    public function __invoke(array $options)
    {
        $validator = $this->validator = $this->is->createValidator();
        
        $validator($options);
        
        return $validator;
    }
}
