<?php
/**
 * Widget Framework
 *
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget\Validator;

/**
 * @package     Widget
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class Blank extends AbstractValidator
{
    protected $blankMessage = '%name% must be blank';
    
    public function __invoke($input)
    {
        if (!$this->isString($input)) {
            $this->addError('notString');
            return false;
        }
        
        if ('' !== trim($input)) {
            $this->addError('blank');
            return false;
        }
        
        return true;
    }
}
