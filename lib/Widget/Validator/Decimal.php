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
class Decimal extends AbstractValidator
{
    protected $invalidMessage = '%name% must be decimal';
    
    protected $notMessage = '%name% must not be decimal';
    
    public function __invoke($input)
    {
        return $this->isValid($input);
    }
    

    /**
     * {@inheritdoc}
     */
    public function validate($input)
    {
        if (!is_numeric($input) || floor($input) == $input) {
            $this->addError('invalid');
        }
    }
}
