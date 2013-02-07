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
class Is extends WidgetProvider
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
     * @var \Widget\Validator\AbstractRule
     * @internal
     */
    private $ruleValidator;
    
    /**
     * @param string $rule
     * @param array|null $data
     * @paran mixed $options
     * @internal Do NOT use this method for it may be change in the future
     */
    public function validateOne($rule, $data, $options = array())
    {
        if ($rule instanceof \Widget\Validator\AbstractRule) {
            $this->ruleValidator = $rule;
            return $rule($data);
        }
         
        // Starts with "not", such as notDigit, notEqual
        if (0 === stripos($rule, 'not')) {
            $reverse = true;
            $rule = substr($rule, 3);
        } else {
            $reverse = false;
        }

        if (!$class = $this->hasRule($rule)) {
            throw new Exception(sprintf('Validator rule "%s" not found', $rule));
        }

        $rv = $this->ruleValidator = new $class(array(
            'widget' => $this->widget
        ));
        
        if (!is_array($options)) {
            $options = array($options);
        }
        
        if (is_int(key($options))) {
            array_unshift($options, $data);
            $result = call_user_func_array($rv, $options);
        } else {
            $rv->option($options);
            $result = $rv($data);
        }

        return $result xor $reverse;
    }

    /**
     * Validate data by given rules
     *
     * @param array $options
     * @param array|null $data
     * @return mixed
     * @throws \InvalidArgumentException
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
        return new Validator(array(
            'widget'    => $this->widget,
            'is'        => $this
        ));
    }
    
    /**
     * @internal
     */
    public function getLastRuleValidator()
    {
        return $this->ruleValidator;
    }
}
