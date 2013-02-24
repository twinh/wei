<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget;

/**
 * The validator manager
 *
 * @package     Widget
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class Is extends AbstractWidget
{
    /**
     * The validator rule alias
     * 
     * @var array
     */
    protected $alias = array(
        'empty'     => 'Widget\Validator\EmptyValue',
        'before'    => 'Widget\Validator\Max',
        'after'     => 'Widget\Validator\Min'
    );
    
    /**
     * @param string|\Widget\Validator\AbstractValidator|int $rule
     * @param array|null $data
     * @paran mixed $options
     * @internal Do NOT use this method for it may be changed in the future
     */
    public function validateOne($rule, $data, $options = array(), &$validator = null, $props = array())
    {
        // Process simple array rules, eg 'username' => ['required', 'email']
        if (is_int($rule)) {
            $rule = $options;
            if (is_string($options)) {
                $options = true;
            }
        }
        
        if ($rule instanceof \Widget\Validator\AbstractValidator) {
            $validator = $rule;
            return $rule($data);
        }
        
        $validator = $this->createRuleValidator($rule, $props);

        if (!is_array($options)) {
            $options = array($options);
        }
        
        if (is_int(key($options))) {
            array_unshift($options, $data);
            $result = call_user_func_array($validator, $options);
        } else {
            $validator->option($options);
            $result = $validator($data);
        }

        return $result;
    }

    /**
     * Validate data by given rules
     * 
     * @param string|\Closure|array $rule The validation rule
     * @param mixed $data The data to be validated
     * @param array $options The validation parameters
     * @return bool
     * @throws UnexpectedTypeException When rule is not string, array or \Closure
     */
    public function __invoke($rule = null, $data = null, $options = array())
    {
        switch (true) {
            // ($rule, $data, $options)
            case is_string($rule) :
                return $this->validateOne($rule, $data, $options);
            // (function(){}, $data)
            case $rule instanceof \Closure :
                return $this->validateOne('callback', $data, $rule);
            // ($rule, $data)
            case is_array($rule):
                return $this->createValidator()->__invoke(array(
                    'rules' => array(
                        'one' => $rule,
                    ),
                    'data' => array(
                        'one' => $data,
                    )
                ));
            default:
                throw new UnexpectedTypeException($rule, 'string, array or \Closure');
        }
    }
    
    /**
     * Check if the validator rule exists
     *
     * @param string $rule
     * @return string|false
     */
    public function hasRule($rule)
    {
        $rule[0] = strtolower($rule[0]);
        if (isset($this->alias[$rule])) {
            $class = $this->alias[$rule];
        } else {
            $class = 'Widget\Validator\\' . ucfirst($rule);
        }
        if (class_exists($class)) {
            return $class;
        } else {
            return false;
        }
    }
   
    /**
     * Create a new validator instance
     * 
     * @return \Widget\Validator
     */
    public function createValidator()
    {
        return $this->widget->newInstance('validator', array(
            'is' => $this
        ));
    }
    
    /**
     * Create a rule validator instance by specified rule name
     * 
     * @param string $rule The name of rule validator
     * @param array $options The property options for rule validator
     * @return Widget\Validator\AbstractValidator
     * @throws Exception When validator not found
     */
    public function createRuleValidator($rule, array $options = array())
    {
        // Starts with "not", such as notDigit, notEqual
        if (0 === stripos($rule, 'not')) {
            $options['opposite'] = true;
            $rule = substr($rule, 3);
        }
        
        if (!$class = $this->hasRule($rule)) {
            throw new Exception(sprintf('Validator "%s" not found', $rule));
        }

        $options = $options + array('widget' => $this->widget) + (array)$this->config('is' . ucfirst($rule));
        
        return new $class($options);
    }
}
