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
class EndsWith extends AbstractValidator
{
    protected $notFoundMessage = '%name% must end with: %findMe%';
    
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

        $pos = strlen($input) - strlen($this->findMe);

        $fn = $this->case ? 'strrpos' : 'strripos';

        if ($pos !== $fn($input, $this->findMe)) {
            $this->addError('notFound', array(
                'findMe' => $this->findMe
            ));
            return false;
        }
        
        return true;
    }
}
