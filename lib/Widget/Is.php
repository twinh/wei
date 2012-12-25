<?php

/**
 * Widget Framework
 * 
 * @copyright   Copyright (c) 2008-2012 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget;

use Widget\Is\Validator;
use InvalidArgumentException;

/**
 * Is
 *
 * @package     Widget
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class Is extends WidgetProvider
{
    protected $internalValidators = array(
        'array' => 'is_array',
        'bool' => 'is_bool',
        'int' => 'is_int',
        'null' => 'is_null',
        'numeric' => 'is_numeric',
        'scalar' => 'is_scalar',
        'string' => 'is_string',
    );
    
    protected $rules = array(
        'qq' => '\Widget\Is\Rule\QQ',
    );
    
    /**
     * The last validator object
     * 
     * @var \Widget\Is\Validator
     */
    public $validator;
    
    public function validateOne($rule, $data, $options = array())
    {
        if (!$class = $this->hasRule($rule)) {
            throw new Exception(sprintf('Rule "%s" not defined', $rule));
        }

        $rule = new $class(array(
            'widget' => $this->widget
        ));

        switch (count($options)) {
            case 0:
                return $rule($data);

            case 1:
                return $rule($data, $options[0]);

            case 2:
                return $rule($data, $options[0], $options[1]);

            default:
                array_unshift($data, $options);
                return call_user_func_array($rule, $options);
        }
    }

    /**
     * Validate data
     * 
     * @param array|string $options
     * @param array $data
     * @return bool
     * @throws \InvalidArgumentException
     * @example $this->is('rule', $data) Validate by one rule
     */
    public function __invoke($options = array(), $data = null)
    {
        switch (true) {
            case is_string($options):
                $args = func_get_args();
                array_shift($args);
                return $this->validateOne($options, $data, $args);
                
            case is_array($options):
                $this->validator = new Validator;
                return $this->validator->__invoke($options);

            default:
                throw new InvalidArgumentException('Parameter 1 shoud be string or array');
        }
    }

    /**
     * Check if the validate rule exists
     * 
     * @param string $rule
     * @return string|boolean
     */
    public function hasRule($rule)
    {
        if (isset($this->rules[$rule])) {
            return $this->rules[$rule];
        } elseif (class_exists($class = '\Widget\Is\Rule\\' . ucfirst($rule))) {
            return $class;
        } else {
            return false;
        }
    }
}
