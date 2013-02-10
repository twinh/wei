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
class MinLength extends AbstractValidator
{
    protected $limit;
    
    protected $message = '%name% must have a length greater than %limit%';

    public function __invoke($input, $limit = null)
    {
        $limit && $this->limit = $limit;
        
        return $this->limit <= Length::getLength($input);
    }
}
