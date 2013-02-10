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
    
    protected $limitMessage = '%name% must have a length greater than %limit%';
    
    protected $limitArrayMessage = '%name% must contain at least %limit% item(s)';

    public function __invoke($input, $limit = null)
    {
        $limit && $this->limit = $limit;
        
        if ($this->limit > Length::getLength($input)) {
            $this->addError(is_scalar($input) ? 'limit' : 'limitArray', array(
                'limit' => $limit
            ));
        }
        
        return !$this->errors;
    }
}
