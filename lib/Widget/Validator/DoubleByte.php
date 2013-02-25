<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget\Validator;

/**
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class DoubleByte extends Regex
{
    protected $patternMessage = '%name% must contain only double byte characters';
    
    protected $notMessage = '%name% must not contain only double byte characters';
    
    protected $pattern = '/^[^\x00-xff]+$/';
}
