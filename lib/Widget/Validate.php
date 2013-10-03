<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Widget;

use Widget\Validator\BaseValidator;

/**
 * A validator service
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class Validate extends Base
{
    /**
     * The validation rules
     *
     * @var array
     */
    protected $rules = array();

    /**
     * The data to be validated
     *
     * @var array
     */
    protected $data = array();

    /**
     * The invalid messages
     *
     * @var array
     */
    protected $messages = array();

    /**
     * The names for messages
     *
     * @var array
     */
    protected $names = array();

    /**
     * The callback triggered before validation
     *
     * @var callable
     */
    protected $beforeValidate;

    /**
     * The callback triggered when every rule is valid
     *
     * @var callable
     */
    protected $ruleValid;

    /**
     * The callback triggered when every rule is invalid
     *
     * @var callable
     */
    protected $ruleInvalid;

    /**
     * The callback triggered when every field is valid
     *
     * @var callable
     */
    protected $fieldValid;

    /**
     * The callback triggered when every field is invalid
     *
     * @var callable
     */
    protected $fieldInvalid;

    /**
     * The callback triggered after all rules are valid
     *
     * @var callable
     */
    protected $success;

    /**
     * The callback triggered when the validation is invalid
     *
     * @var callable
     */
    protected $failure;

    /**
     * Whether break the validation flow when any field's rule is not valid
     *
     * @var bool
     */
    protected $breakRule = false;

    /**
     * Whether break the validation flow when any field is not valid
     *
     * @var bool
     */
    protected $breakField = false;

    /**
     * Whether skip the current field validation when the filed's rule is not
     * valid, so every field contains one invalid rule at most
     *
     * @var bool
     */
    protected $skip = false;

    /**
     * The valid rules array, which use the field as key, and the rules as value
     *
     * @var string
     */
    protected $validRules = array();

    /**
     * The invalid rules array, which use the field as key, and the rules as value
     *
     * @var string
     */
    protected $invalidRules = array();

    /**
     * The validation result
     *
     * @var bool|null
     */
    protected $result;

    /**
     * The rule validator instances
     *
     * @var array<Validator\BaseValidator>
     */
    protected $ruleValidators = array();

    /**
     * Create a new validator and validate by specified options
     *
     * @param array $options
     * @return $this
     */
    public function __invoke(array $options = array())
    {
        $validator = new self($options + get_object_vars($this));

        $validator->valid($options);

        return $validator;
    }

    /**
     * Validate the data by the given options
     *
     * @param array $options The options for validation
     * @return bool Whether pass the validation or not
     * @throws \InvalidArgumentException  When validation rule is not array, string or instance of BaseValidator
     */
    public function valid($options = array())
    {
        $options && $this->setOption($options);

        // Initialize the validation result to be true
        $this->result = true;

        $this->beforeValidate && call_user_func($this->beforeValidate, $this, $this->widget);

        foreach ($this->rules as $field => $rules) {
            $data = $this->getFieldData($field);

            /**
             * Process simple rule
             * FROM
             * 'username' => 'required'
             * TO
             * 'username' => array(
             *     'required' => true
             * )
             */
            if (is_string($rules)) {
                $rules = array($rules => true);
            } elseif ($rules instanceof BaseValidator) {
                $rules = array($rules);
            } elseif (!is_array($rules)) {
                throw new \InvalidArgumentException(sprintf(
                    'Expected argument of type array, string or instance of ValidatorInterface, "%s" given',
                    is_object($rules) ? get_class($rules) : gettype($rules)
                ));
            }

            // Make sure the "required" rule at first
            if (!isset($rules['required'])) {
                $isRequired = true;
            } else {
                $isRequired = (bool) $rules['required'];
                unset($rules['required']);
            }
            $rules = array('required' => $isRequired) + $rules;

            // Start validation
            foreach ($rules as $rule => $params) {
                // Prepare property options for validator
                $props = $this->prepareProps($field, $rule);

                // The current rule validation result
                /* @var $validator Validator\BaseValidator */
                $validator = null;
                $result = $this->validateOne($rule, $data, $params, $validator, $props);

                if (is_object($params)) {
                    $rule = get_class($params);
                }

                // Record the rule validators
                $this->ruleValidators[$field][$rule] = $validator;

                // If any rule is invalid, the result would always be false in the whole validation flow
                if (false === $result) {
                    $this->result = false;
                }

                // Record the valid/invalid rule
                $method = $result ? 'addValidRule' : 'addInvalidRule';
                $this->$method($field, $rule);

                // Trigger the ruleValid/ruleInvalid callback
                $callback = $result ? 'ruleValid' : 'ruleInvalid';
                if ($this->$callback && false === call_user_func($this->$callback, $rule, $field, $this, $this->widget)) {
                    return $this->result;
                }

                if ($result) {
                    // The field data is empty and optional, skip the remaining validation rules
                    if ('required' === $rule && $validator->isInvalid($data)) {
                        break;
                    }
                } else {
                    // Break the validation flow when any field's rule is invalid
                    if ($this->breakRule || $this->skip) {
                        break;
                    }
                }
            }

            // Trigger the fieldValid/fieldInvalid callback
            $callback = $this->isFieldValid($field) ? 'fieldValid' : 'fieldInvalid';
            if ($this->$callback && false === call_user_func($this->$callback, $field, $this, $this->widget)) {
                return $this->result;
            }

            if (!$this->result && $this->skip) {
                continue;
            }

            // Break the validation flow when any field is invalid
            if (!$this->result && ($this->breakRule || $this->breakField)) {
                break;
            }
        }

        // Trigger the success/failure callback
        $callback = $this->result ? 'success' : 'failure';
        $this->$callback && call_user_func($this->$callback, $this, $this->widget);

        return $this->result;
    }

    /**
     * Prepare name and messages property option for rule validator
     *
     * @param string $field
     * @param string $rule
     * @return array
     */
    protected function prepareProps($field, $rule)
    {
        $props = $messages = array();

        $props['validator'] = $this;

        // Prepare name for validator
        if (isset($this->names[$field])) {
            $props['name'] = $this->names[$field];
        }

        /**
         * Prepare messages for validator
         *
         * The messages array may look like below
         * array(
         *     // Case 1
         *     'field' => 'message',
         *     // Case 2
         *     'field2' => array(
         *         'rule' => 'message'
         *     ),
         *     // Case 2
         *     'field3' => array(
         *         'rule' => array(
         *            'option' => 'message',
         *            'option2' => 'message',
         *         )
         *     )
         * )
         *
         * In case 2, checking non-numeric offsets of strings would return true
         * in PHP 5.3, while return false in PHP 5.4, so we do NOT known
         * $messages is array or string
         * @link http://php.net/manual/en/function.isset.php
         *
         * In case 1, $messages is string
         */
        // Case 2
        if (isset($this->messages[$field][$rule]) && is_array($this->messages[$field])) {
            $messages = $this->messages[$field][$rule];
        // Case 1
        } elseif (isset($this->messages[$field]) && is_scalar($this->messages[$field])) {
            $messages = $this->messages[$field];
        }

        // Convert message to array for validator
        if (is_scalar($messages)) {
            $props['message'] =  $messages;
        } elseif (is_array($messages)) {
            foreach ($messages as $name => $message) {
                $props[$name . 'Message'] = $message;
            }
        }

        return $props;
    }

    /**
     * Add valid rule
     *
     * @param string $field The field name
     * @param string $rule The rule name
     * @return $this
     */
    public function addValidRule($field, $rule)
    {
        $this->validRules[$field][] = $rule;
        return $this;
    }

    /**
     * Add invalid rule
     *
     * @param string $field The field name
     * @param string $rule The rule name
     * @return $this
     */
    public function addInvalidRule($field, $rule)
    {
        $this->invalidRules[$field][] = $rule;
        return $this;
    }

    /**
     * Returns the valid fields
     *
     * @return array
     */
    public function getValidFields()
    {
        return array_keys(array_diff_key($this->validRules, $this->invalidRules));
    }

    /**
     * Returns the invalid fields
     *
     * @return array
     */
    public function getInvalidFields()
    {
        return array_keys($this->invalidRules);
    }

    /**
     * Check if field is valid
     *
     * @param string $field
     * @return bool
     */
    public function isFieldValid($field)
    {
        return !in_array($field, $this->getInvalidFields());
    }

    /**
     * Check if field is invalid
     *
     * @param string $field
     * @return bool
     */
    public function isFieldInvalid($field)
    {
        return in_array($field, $this->getInvalidFields());
    }

    /**
     * Set field rules
     *
     * @param array $rules
     * @return $this
     */
    public function setRules(array $rules = null)
    {
        $this->rules = (array)$rules;
        return $this;
    }

    /**
     * Get validator rules
     *
     * @return array
     */
    public function getRules()
    {
        return $this->rules;
    }

    /**
     * Get validator rules by specified field
     *
     * @param string $field
     * @return array
     */
    public function getFieldRules($field)
    {
        return isset($this->rules[$field]) ? $this->rules[$field] : array();
    }

    /**
     * Get validation rule parameters
     *
     * @param string $field The validation field
     * @param string $rule The validation rule
     * @return array
     */
    public function getRuleParams($field, $rule)
    {
        return isset($this->rules[$field][$rule]) ? (array) $this->rules[$field][$rule] : array();
    }

    /**
     * Get valid rules by field
     *
     * @param string $field
     * @return array
     */
    public function getValidRules($field)
    {
        return isset($this->validRules[$field]) ? $this->validRules[$field] : array();
    }

    /**
     * Get invalid rules by field
     *
     * @param string $field
     * @return array
     */
    public function getInvalidRules($field = null)
    {
        return $field ?
            isset($this->invalidRules[$field]) ? $this->invalidRules[$field] : array()
            : $this->invalidRules;
    }

    /**
     * Returns the validation result
     *
     * @return bool
     */
    public function isValid()
    {
        return is_null($this->result) ? $this->__invoke() : $this->result;
    }

    /**
     * Adds rule for specified field
     *
     * @param string $field The name of field
     * @param string $rule The name of rule
     * @param mixed $parameters The parameters for rule
     */
    public function addRule($field, $rule, $parameters)
    {
        $this->rules[$field][$rule] = $parameters;
    }

    /**
     * Returns whether the validation rule exists in specified field
     *
     * @param string $field
     * @param string $rule
     * @return bool
     */
    public function hasRule($field, $rule)
    {
        return isset($this->rules[$field][$rule]);
    }

    /**
     * Removes the rule in field
     *
     * @param string $field The name of field
     * @param string $rule The name of rule
     * @return bool
     */
    public function removeRule($field, $rule)
    {
        if (isset($this->rules[$field][$rule])) {
            unset($this->rules[$field][$rule]);
            return true;
        }
        return false;
    }

    /**
     * Removes the validate field
     *
     * @param string $field
     * @return bool
     */
    public function removeField($field)
    {
        if (isset($this->rules[$field])) {
            unset($this->rules[$field]);
            return true;
        }
        return false;
    }

    /**
     * Sets data for validation
     *
     * @param array|object $data
     * @throws \InvalidArgumentException when argument type is not array or object
     * @return $this
     */
    public function setData($data)
    {
        if (!is_array($data) && !is_object($data)) {
            throw new \InvalidArgumentException(sprintf(
                'Expected argument of type array or object, "%s" given',
                is_object($data) ? get_class($data) : gettype($data)
            ));
        }
        $this->data = $data;
        return $this;
    }

    /**
     * Returns validation data
     *
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Returns validation field data
     *
     * @param string $field The name of field
     * @return mixed
     */
    public function getFieldData($field)
    {
        // $this->data could only be array or object, which has been checked by $this->setData
        if ((is_array($this->data) && array_key_exists($field, $this->data))
            || ($this->data instanceof \ArrayAccess && $this->data->offsetExists($field))
        ) {
            return $this->data[$field];
        } elseif (isset($this->data->$field)) {
            return $this->data->$field;
        } elseif (method_exists($this->data, 'get' . $field)) {
            return $this->data->{'get' . $field}();
        } else {
            return null;
        }
    }

    /**
     * Sets data for validation field
     *
     * @param string $field The name of field
     * @param mixed $data The data of field
     * @return $this
     */
    public function setFieldData($field, $data)
    {
        if (is_array($this->data)) {
            $this->data[$field] = $data;
        } else {
            $this->data->$field = $data;
        }
        return $this;
    }

    /**
     * Set custom messages
     *
     * @param array $messages
     * @return $this
     */
    public function setMessages(array $messages = null)
    {
        $this->messages = (array)$messages;
        return $this;
    }

    /**
     * Returns custom message
     *
     * @return array
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * Returns detail invalid messages
     *
     * @return array
     */
    public function getDetailMessages()
    {
        $messages = array();
        foreach ($this->invalidRules as $field => $rules) {
            foreach ($rules as $rule) {
                $messages[$field][$rule] = $this->ruleValidators[$field][$rule]->getMessages();
            }
        }
        return $messages;
    }

    /**
     * Returns summary invalid messages
     *
     * @return array
     */
    public function getSummaryMessages()
    {
        $messages = $this->getDetailMessages();
        $summaries = array();
        foreach ($messages as $field => $rules) {
            foreach ($rules as $options) {
                foreach ($options as $message) {
                    $summaries[$field][] = $message;
                }
            }
        }
        return $summaries;
    }

    /**
     * Returns error message string connected by specified separator
     *
     * @param string $separator
     * @return string
     */
    public function getJoinedMessage($separator = "\n")
    {
        $messages = $this->getDetailMessages();
        $array = array();
        foreach ($messages as $rules) {
            foreach ($rules as $options) {
                foreach ($options as $message) {
                    $array[] = $message;
                }
            }
        }
        return implode($separator, array_unique($array));
    }

    /**
     * Returns the first error message string
     *
     * @return false|string
     */
    public function getFirstMessage()
    {
        if ($this->isValid()) {
            return false;
        }
        return current(current(current($this->getDetailMessages())));
    }

    /**
     * Returns the rule validator object
     *
     * @param string $field
     * @param string $rule
     * @return Validator\BaseValidator
     */
    public function getRuleValidator($field, $rule)
    {
        return isset($this->ruleValidators[$field][$rule]) ? $this->ruleValidators[$field][$rule] : null;
    }

    /**
     * Sets field names
     *
     * @param array $names
     */
    public function setNames($names)
    {
        $this->names = (array)$names;
    }

    /**
     * Returns field names
     *
     * @return array
     */
    public function getNames()
    {
        return $this->names;
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

        $object = 'is' . ucfirst($rule);
        $class = $this->widget->getClass($object);
        if (!$class || !class_exists($class)) {
            throw new \InvalidArgumentException(sprintf('Validator "%s" not found', $rule));
        }


        $options = $options + array('widget' => $this->widget) + (array)$this->widget->getConfig('is' . ucfirst($rule));

        return new $class($options);
    }
}