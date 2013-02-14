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
class StartsWith extends AbstractValidator
{
    protected $notFoundMessage = '%name% must start with: %findMe%';
    
    protected $findMe;
    
    protected $case = false;
    
    public function __invoke($input, $findMe = null, $case = null)
    {
        $findMe && $this->findMe = $findMe;
        is_bool($case) && $this->case = $case;
        
        if (!$this->isString($input)) {
            $this->addError('notString');
            return false;
        }
        
        $fn = $this->case ? 'strpos' : 'stripos';

        if (0 !== $fn($input, $this->findMe)) {
            $this->addError('notFound', array(
                'findMe' => $this->findMe
            ));
            return false;
        }
        
        return true;
    }
}
