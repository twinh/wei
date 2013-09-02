<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Widget;

/**
 * The validator manager, use to validate input quickly, create validator
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class Is extends Base
{
    /**
     * The validator rule aliases
     *
     * @var array
     */
    protected $aliases = array(
        'empty'     => 'Widget\Validator\EmptyValue',
        'before'    => 'Widget\Validator\Max',
        'after'     => 'Widget\Validator\Min'
    );

    /**
     * The available validator rules name
     *
     * @var array
     */
    protected $rules = array(
        'All', 'AllOf', 'Alnum', 'Alpha', 'Between', 'Blank', 'Callback',
        'CharLength', 'Chinese', 'Color', 'CreditCard', 'Date', 'DateTime',
        'Decimal', 'Digit', 'Dir', 'DivisibleBy', 'DoubleByte', 'Email',
        'EndsWith', 'EqualTo', 'Exists', 'File', 'IdCardCn', 'IdCardHk',
        'IdCardMo', 'IdCardTw', 'Image', 'In', 'Ip', 'Length', 'Lowercase',
        'Max', 'MaxLength', 'Min', 'MinLength', 'MobileCn', 'NoneOf', 'Null',
        'Number', 'OneOf', 'PhoneCn', 'PlateNumberCn', 'Postcode', 'QQ',
        'RecordExists', 'Regex', 'Required', 'SomeOf', 'StartsWith', 'Time',
        'Tld', 'Type', 'Uppercase', 'Url', 'Uuid',
        // Comparison
        'IdenticalTo',
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
        foreach ($this->aliases as $rule => $class) {
            $rules['is' . ucfirst($rule)] = $class;
        }
        $this->widget->setAliases($rules);
    }

    /**
     * @param string|Validator\BaseValidator|int $rule
     * @param array|null $input
     * @param mixed $options
     * @param null &$validator
     * @param array $props
     * @return bool
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

        if ($rule instanceof Validator\BaseValidator) {
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
     * Validate input by given rule
     *
     * @param string|\Closure|array $rule The validation rule
     * @param mixed $input The data to be validated
     * @param array $options The validation parameters
     * @return bool
     * @throws \InvalidArgumentException When rule is not string, array or \Closure
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
                $validator = $this->validate(array(
                    'rules' => array(
                        'one' => $rule,
                    ),
                    'data' => array(
                        'one' => $input,
                    )
                ));
                return $validator->isValid();
            default:
                throw new \InvalidArgumentException(sprintf(
                    'Expected argument of type string, array or \Closure, "%s" given',
                    is_object($rule) ? get_class($rule) : gettype($rule)
                ));
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
        $rule = lcfirst($rule);
        if (isset($this->aliases[$rule])) {
            $class = $this->aliases[$rule];
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
     * Create a rule validator instance by specified rule name
     *
     * @param string $rule The name of rule validator
     * @param array $options The property options for rule validator
     * @return Validator\BaseValidator
     * @throws \InvalidArgumentException When validator not found
     */
    public function createRuleValidator($rule, array $options = array())
    {
        // Starts with "not", such as notDigit, notEqual
        if (0 === stripos($rule, 'not')) {
            $options['negative'] = true;
            $rule = substr($rule, 3);
        }

        if (!$class = $this->hasRule($rule)) {
            throw new \InvalidArgumentException(sprintf('Validator "%s" not found', $rule));
        }

        $options = $options + array('widget' => $this->widget) + (array)$this->widget->getConfig('is' . ucfirst($rule));

        return new $class($options);
    }
}
