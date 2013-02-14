<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget;

use Widget\Validator\AbstractValidator;

/**
 * Validator
 *
 * @package     Widget
 * @subpackage  Validation
 * @author      Twin Huang <twinh@yahoo.cn>
 * @property    \Widget\Is $is The validator manager
 */
class Validator extends AbstractValidator
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
     * Whether break the validation flow when any field is not valid
     *
     * @var bool
     */
    protected $breakField = false;

    /**
     * Whether break the validation flow when any field's rule is not valid
     *
     * @var bool
     */
    protected $breakRule = false;

    /**
     * Whether skip the crruent field validation when the filed's rule is not 
     * valid, so every field contains one invalid rule at most
     * 
     * @var bool
     */
    protected $skip = false;
    
    /**
     * The callback method triggered when every rule is valid
     *
     * @var null|callback
     */
    protected $ruleValid = null;

    /**
     * The callback method triggered when every rule is invalid
     *
     * @var null|callback
     */
    protected $ruleInvalid = null;

    /**
     * The callback method triggered when every field is valid
     *
     * @var null|callback
     */
    protected $fieldValid = null;

    /**
     * The callback method triggered when every field is invalid
     *
     * @var null|callback
     */
    protected $fieldInvalid = null;

    /**
     * The callback method triggered after all rules are valid
     *
     * @var null|callback
     */
    protected $success = null;

    /**
     * The callback method triggered when the validation is invalid
     *
     * @var null|callback
     */
    protected $failure = null;

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
     * @var array<\Widget\Validator\AbstractRule>
     */
    protected $ruleValidators = array();
    
    /**
     * The names for messaages
     * 
     * @var array
     */
    protected $names = array();

    /**
     * Validate the data by the given options
     *
     * @param array $options The options for validation
     * @return false Whether pass the validation or not
     * @throws \InvalidArgumentException When validation rules is empty
     */
    public function __invoke($options = array())
    {
        $this->option($options);

        if (empty($this->rules)) {
            throw new \InvalidArgumentException('Validation rules should not be empty.');
        }
        
        // Initialize the validation result to be true
        $this->result = true;

        foreach ($this->rules as $field => $rules) {
            $data = $this->getFieldData($field);
            
            /**
             * Process simple rule
             * FROM
             * 'username' => 'required'
             * TO
             * 'username' => array(
             *     'require' => true
             * )
             */
            if (is_string($rules)) {
                $rules = array($rules => true);
            } elseif (!is_array($rules)) {
                throw new UnexpectedTypeException($rules, 'array or string');
            }

            // Make sure the "required" rule at first
            if (!isset($rules['required'])) {
                $value = true;
            } else {
                $value = (bool) $rules['required'];
                unset($rules['required']);
            }
            $rules = array('required' => $value) + $rules;

            // Start validation
            foreach ($rules as $rule => $params) {
                // Prepare property options for validator
                $props = array();
                if (isset($this->names[$field])) {
                    $props['name'] = $this->names[$field];
                }
                $messages = $this->getRuleMessage($field, $rule);
                if (is_string($messages)) {
                    $props['message'] =  $messages;
                } elseif (is_array($messages)) {
                    foreach ($messages as $name => $message) {
                        $props[$name . 'Message'] = $message;
                    }
                }
                
                // The current rule validation result
                /* @var $validator \Widget\Validator\AbstractValidator */
                $validator = null;
                $result = $this->is->validateOne($rule, $data, $params, $validator, $props);

                if (is_object($rule)) {
                    $rule = get_class($rule);
                }
                
                // Record the rule validators
                $this->ruleValidators[$field][$rule] = $validator;
                
                // If any rule is invlid, the result would always be false in the whole validation flow
                if (false === $result) {
                    $this->result = false;
                }

                // Record the valid/invalid rule
                $method = $result ? 'addValidRule' : 'addInvalidRule';
                $this->$method($field, $rule);

                // Trigger the ruleValid/ruleInvalid callback
                $callback = $result ? 'ruleValid' : 'ruleInvalid';
                if (false === $this->callback($this->$callback, array($field, $rule, $this))) {
                    return $this->result;
                }

                if ($result) {
                    // The field data is empty and optional, skip the remaining validation rules
                    if (!$data && 'required' === $rule) {
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
            if (false === $this->callback($this->$callback, array($field, $this))) {
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
        $this->callback($this->$callback, array($this));

        return $this->result;
    }

    /**
     * Add valid rule
     *
     * @param string $field The field name
     * @param string $rule The rule name
     * @return Validator
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
     * @return Validator
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
    public function isFieldInvalidted($field)
    {
        return in_array($field, $this->getInvalidFields());
    }

    /**
     * Get valid rules by field name
     *
     * @param string $field The valid field
     * @return array
     */
    public function getRules($field)
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
     * Call a callback with an array of parameters, if the callback is empty,
     * returns true instead
     *
     * @param callback $callback The callable to be called
     * @param array $params The parameters to be passed to the callback, as an
     *                      indexed array.
     * @return mixed
     * @throws \InvalidArgumentException When the first argument is not callable
     */
    protected function callback($callback, array $params)
    {
        // Returns true when callback is not set
        if (!$callback) {
            return true;
        }

        if (!is_callable($callback)) {
            throw new \InvalidArgumentException('The first argument is not callable');
        }

        return call_user_func_array($callback, $params);
    }
    
    /**
     * {@inheritdoc}
     */
    public function isValid($input = null)
    {
        return $this->result;
    }
    
    /**
     * Adds rule for specified field
     * 
     * @param string $field The name of field
     * @param string $rule The name of rule
     * @param mixed $options The parameters for rule
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
     * Sets data for validation
     * 
     * @param array|object $data
     * @return \Widget\Validator
     */
    public function setData($data)
    {
        if (!is_array($data) && !is_object($data)) {
            throw new UnexpectedTypeException($data, 'array or object');
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
        if (is_array($this->data) && array_key_exists($field, $this->data)) {
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
     * Set custome messages
     * 
     * @param array $messages
     * @todo confict with interface
     */
    public function setMessages(array $messages)
    {
        $this->messages = $messages;
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
    
    public function getRuleMessage($field, $rule)
    {
        if (isset($this->messages[$field][$rule]) && is_array($this->messages[$field])) {
            return $this->messages[$field][$rule];
        } elseif (isset($this->messages[$field]) && is_string($this->messages[$field])) {
            return $this->messages[$field];
        } else {
            return false;
        }
    }
    
    /**
     * Returns the message by given field, rule and option
     * 
     * @param string $field The field of data
     * @param string $rule The rule of field
     * @param string $option The option of rule
     * @return string|false
     */
    public function getMessage($field, $rule, $option)
    {
        if (isset($this->messages[$field][$rule][$option]) && is_string($this->messages[$field][$rule][$option]) && is_array($this->messages[$field][$rule])) {
            return $this->messages[$field][$rule][$option];
        } elseif (isset($this->messages[$field][$rule]) && is_string($this->messages[$field][$rule]) && is_array($this->messages[$field])) {
            return $this->messages[$field][$rule];
        } elseif (isset($this->messages[$field]) && is_string($this->messages[$field])) {
            return $this->messages[$field];
        } else {
            return false;
        }
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
     * 
     * @param type $field
     * @param type $rule
     * @return \Widget\Validator\AbstractRule
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
}
