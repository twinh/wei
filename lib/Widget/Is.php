<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget;

use Widget\Exception\UnexpectedTypeException;
use Widget\Exception\InvalidArgumentException;

/**
 * The validator manager, use to validate data quickly, create validator and 
 * rule validator
 *
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
    
    private $rules = array(
        'All', 'AllOf', 'Alnum', 'Alpha', 'Blank', 'Callback', 'Chinese', 
        'CreditCard', 'Date', 'DateTime', 'Decimal', 'Digit', 'Dir', 
        'DivisibleBy', 'DoubleByte', 'Email', 'EndsWith', 'EntityExists', 
        'Equals', 'Exists', 'File', 'IdCardCn', 'IdCardHk', 'IdCardMo', 
        'IdCardTw', 'Image', 'In', 'Ip', 'Length', 'Lowercase', 'Max', 
        'MaxLength', 'Min', 'MinLength', 'MobileCn', 'NoneOf', 'Null', 'Number', 
        'OneOf', 'PhoneCn', 'PlateNumberCn', 'Postcode', 'QQ', 'Range', 'Regex', 
        'Required', 'SomeOf', 'StartsWith', 'Time', 'Tld', 'Type', 'Uppercase', 
        'Url', 'Uuid'
    );

    /**
     * Constructor
     * 
     * @param array $options
     */
    public function __construct(array $options = array())
    {
        parent::__construct($options);
        
        // Adds widget alias for validators
        $rules = array();
        foreach ($this->rules as $rule) {
            $rules['is' . $rule] = 'Widget\Validator\\' . $rule;
        }
        foreach ($this->alias as $rule => $class) {
            $rules['is' . ucfirst($rule)] = $class;
        }
        $this->widget->appendOption('alias', $rules);
    }
    
    /**
     * @param string|\Widget\Validator\AbstractValidator|int $rule
     * @param array|null $input
     * @paran mixed $options
     * @internal Do NOT use this method for it may be changed in the future
     */
    public function validateOne($rule, $input, $options = array(), &$validator = null, $props = array())
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
            return $rule($input);
        }
        
        $validator = $this->createRuleValidator($rule, $props);

        if (!is_array($options)) {
            $options = array($options);
        }
        
        if (is_int(key($options))) {
            array_unshift($options, $input);
            $result = call_user_func_array($validator, $options);
        } else {
            $validator->setOption($options);
            $result = $validator($input);
        }

        return $result;
    }

    /**
     * Validate data by given rule
     * 
     * @param string|\Closure|array $rule The validation rule
     * @param mixed $input The data to be validated
     * @param array $options The validation parameters
     * @return bool
     * @throws \Widget\Exception\UnexpectedTypeException When rule is not string, array or \Closure
     */
    public function __invoke($rule = null, $input = null, $options = array())
    {
        switch (true) {
            // ($rule, $input, $options)
            case is_string($rule) :
                return $this->validateOne($rule, $input, $options);
            // (function(){}, $input)
            case $rule instanceof \Closure :
                return $this->validateOne('callback', $input, $rule);
            // ($rule, $input)
            case is_array($rule):
                return $this->createValidator()->__invoke(array(
                    'rules' => array(
                        'one' => $rule,
                    ),
                    'data' => array(
                        'one' => $input,
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
     * @throws \Widget\Exception\InvalidArgumentException When validator not found
     */
    public function createRuleValidator($rule, array $options = array())
    {
        // Starts with "not", such as notDigit, notEqual
        if (0 === stripos($rule, 'not')) {
            $options['negative'] = true;
            $rule = substr($rule, 3);
        }
        
        if (!$class = $this->hasRule($rule)) {
            throw new InvalidArgumentException(sprintf('Validator "%s" not found', $rule));
        }

        $options = $options + array('widget' => $this->widget) + (array)$this->widget->config('is' . ucfirst($rule));
        
        return new $class($options);
    }
}
