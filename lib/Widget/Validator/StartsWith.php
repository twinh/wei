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
class StartsWith extends AbstractValidator
{
    protected $notFoundMessage = '%name% must start with: %findMe%';
    
    protected $notMessage = '%name% must not start with "%findMe%"';
    
    protected $findMe;
    
    protected $case = false;
    
    public function __invoke($input, $findMe = null, $case = null)
    {
        $findMe && $this->setOption('findMe', $findMe);
        is_bool($case) && $this->setOption('case', $case);
        
        return $this->isValid($input);
    }
    
    /**
     * {@inheritdoc}
     */
    protected function validate($input)
    {
        if (!$this->isString($input)) {
            $this->addError('notString');
            return false;
        }
        
        if (is_string($this->findMe)) {
            $fn = $this->case ? 'strpos' : 'stripos';
            if (0 !== $fn($input, $this->findMe)) {
                $this->addError('notFound');
                return false;
            }
        } elseif (is_array($this->findMe)) {
            $pattern = '/^' . implode('|', $this->findMe) . '/';
            if (!$this->case) {
                $pattern = $pattern . 'i';
            }
            if (!preg_match($pattern, $input)) {
                $this->addError('notFound');
                return false;
            }
        }
        
        return true;
    }
}
