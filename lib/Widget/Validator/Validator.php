<?php

/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget\Validator;

use Widget\WidgetProvider;

/**
 * Validator
 *
 * @package     Widget
 * @subpackage  Validation
 * @author      Twin Huang <twinh@yahoo.cn>
 * @property    \Widget\Is $is The validator manager
 */
class Validator extends WidgetProvider
{
    /**
     * The validate rules
     *
     * @var array
     */
    protected $rules = array();
    
    /**
     * The rule validator instances
     * 
     * @var array<\Widget\Validator\AbstractRule>
     */
    protected $ruleValidators = array();

    /**
     * The data to be validated
     *
     * @var array
     */
    protected $data = array();

    /**
     * The invalidated messages
     *
     * @var array
     */
    protected $messages = array();

    /**
     * Whether break the validation flow when any field is invalidated
     *
     * @var bool
     */
    protected $break = false;

    /**
     * Whether break the validation flow when any field's rule is invalidated
     *
     * @var bool
     */
    protected $breakOne = false;

    /**
     * The callback method triggered when every rule is validated
     *
     * @var null|callback
     */
    protected $validatedOne = null;

    /**
     * The callback method triggered when every rule is invalidated
     *
     * @var null|callback
     */
    protected $invalidatedOne = null;

    /**
     * The callback method triggered when every field is validated
     *
     * @var null|callback
     */
    protected $validated = null;

    /**
     * The callback method triggered when every field is invalidated
     *
     * @var null|callback
     */
    protected $invalidated = null;

    /**
     * The callback method triggered after all rules are validated
     *
     * @var null|callback
     */
    protected $success = null;

    /**
     * The callback method triggered when the whole validation is invalidated
     *
     * @var null|callback
     */
    protected $failure = null;

    /**
     * The validated rules array, which use the field as key, and the rules as value
     *
     * @var string
     */
    protected $validatedRules = array();

    /**
     * The invalidated rules array, which use the field as key, and the rules as value
     *
     * @var string
     */
    protected $invalidatedRules = array();

    /**
     * The validate result
     *
     * @var bool
     */
    protected $result = true;
    
    /**
     * The language code for message
     * 
     * @var string
     */
    protected $language;
    
    /**
     * The language file path
     * 
     * @var string
     */
    private $languageFile;

    /**
     * Validate the data by the given options
     *
     * @param array $options The options for validation
     * @return false Whether pass the validation or not
     * @throws \InvalidArgumentException When validate rules is empty
     */
    public function __invoke($options = array())
    {
        $this->option($options);

        if (empty($this->rules)) {
            throw new \InvalidArgumentException('Validate rules should not be empty.');
        }

        foreach ($this->rules as $field => $rules) {
            $data = isset($this->data[$field]) ? $this->data[$field] : null;
            
            // Process simple rule, eg 'username' => 'required'
            if (!is_array($rules)) {
                $rules = array($rules);
            }

            // Make sure the required rule at first
            if (!isset($rules['required'])) {
                $value = true;
            } else {
                $value = (bool) $rules['required'];
                unset($rules['required']);
            }
            $rules = array('required' => $value) + $rules;

            // Start validate
            foreach ($rules as $rule => $params) {
                // Process simple array rule, eg 'username' => ['required', 'email']
                if (is_int($rule)) {
                    $rule = $params;
                    if (is_string($params)) {
                        $params = true;
                    }
                }
                
                // Prepare parameters for validte widget
                if (!is_bool($params)) {
                    $params = (array) $params;
                }

                // The current rule validate result
                $result = $this->is->validateOne($rule, $data, $params);

                if (is_object($rule)) {
                    $rule = get_class($rule);
                }
                
                // Record the rule validators
                $this->ruleValidators[$field][$rule] = $this->is->getLastRuleValidator();
                
                // Would always be false in the whole validate flow
                if (false === $result) {
                    $this->result = false;
                }

                // Record the validated/invalidated rule
                $method = $result ? 'addValidatedRule' : 'addInvalidatedRule';
                $this->$method($field, $rule);

                // Trigger the validatedOne/invalidatedOne callback
                $callback = $result ? 'validatedOne' : 'invalidatedOne';
                if (false === $this->callback($this->$callback, array($field, $rule, $this))) {
                    return $this->result;
                }

                if ($result) {
                    // The field data is empty and optional, pass the left validate rules
                    if (!$data && 'required' === $rule) {
                        break;
                    }
                } else {
                    // Break the validation flow when any field's rule is invalidated
                    if ($this->breakOne) {
                        break;
                    }
                }
            }
            
            // Trigger the validated/invalidated callback
            $callback = $this->isFieldValidated($field) ? 'validated' : 'invalidated';
            if (false === $this->callback($this->$callback, array($field, $this))) {
                return $this->result;
            }

            // Break the validation flow when any field is invalidated
            if (!$this->result && ($this->breakOne || $this->break)) {
                break;
            }
        }

        // Trigger the success/failure callback
        $callback = $this->result ? 'success' : 'failure';
        $this->callback($this->$callback, array($this));

        return $this->result;
    }

    /**
     * Add validated rule
     *
     * @param string $field The field name
     * @param string $rule The rule name
     * @return Validator
     */
    public function addValidatedRule($field, $rule)
    {
        $this->validatedRules[$field][] = $rule;

        return $this;
    }

    /**
     * Add invalidated rule
     *
     * @param string $field The field name
     * @param string $rule The rule name
     * @return Validator
     */
    public function addInvalidatedRule($field, $rule)
    {
        $this->invalidatedRules[$field][] = $rule;

        return $this;
    }
    
    /**
     * Returns the validated fields
     * 
     * @return array
     */
    public function getValidateFields()
    {
        return array_keys(array_diff_key($this->validatedRules, $this->invalidatedRules));
    }
    
    /**
     * Returns the invalidated fields
     * 
     * @return array
     */
    public function getInvalidatedFields()
    {
        return array_keys($this->invalidatedRules);
    }

    /**
     * Check if field validted
     *
     * @param string $field
     * @return bool
     */
    public function isFieldValidated($field)
    {
        return !in_array($field, $this->getInvalidatedFields());
    }

    /**
     * Check if field invalidated
     *
     * @param string $field
     * @return bool
     */
    public function isFieldInvalidted($field)
    {
        return in_array($field, $this->getInvalidatedFields());
    }

    /**
     * Get validate rules by field name
     *
     * @param string $field The validate field
     * @return array
     */
    public function getRules($field)
    {
        return isset($this->rules[$field]) ? $this->rules[$field] : array();
    }

    /**
     * Get validate rule parameters
     *
     * @param string $field The validate field
     * @param string $rule The validate rule
     * @return array
     */
    public function getRuleParams($field, $rule)
    {
        return isset($this->rules[$field][$rule]) ? (array) $this->rules[$field][$rule] : array();
    }

    /**
     * Get validated rules by field
     *
     * @param string $field
     * @return array
     */
    public function getValidatedRules($field)
    {
        return isset($this->validatedRules[$field]) ? $this->validatedRules[$field] : array();
    }

    /**
     * Get Invalidated rules by field
     *
     * @param string $field
     * @return array
     */
    public function getInvalidatedRules($field)
    {
        return isset($this->invalidatedRules[$field]) ? $this->invalidatedRules[$field] : array();
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
     * Returns the validate result
     * 
     * @return bool
     */
    public function valid()
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
     * Returns whether the validate rule exists in specified field
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
     * Sets data for validate
     * 
     * @param array $data
     * @return \Widget\Validator\Validator
     */
    public function setData(array $data)
    {
        $this->data = $data;
        
        return $this;
    }
    
    /**
     * Returns validate data
     * 
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }
    
    /**
     * Returns validate field data
     *
     * @param string $field The name of field
     * @return mixed
     */
    public function getFieldData($field)
    {
        return isset($this->data[$field]) ? $this->data[$field] : null;
    }
    
    /**
     * Sets data for validate field
     * 
     * @param string $field The name of field
     * @param mixed $data The data of field
     */
    public function setFieldData($field, $data)
    {
        $this->data[$field] = $data;
    }
    
    /**
     * Set custome messages
     * 
     * @param array $messages
     */
    public function setMessages(array $messages)
    {
        $this->messages = $messages;
    }
    
    /**
     * Returns custome message
     * 
     * @return array
     */
    public function getMessages()
    {
        return $this->messages;
    }
    
    /**
     * Returns invalidated messages
     * 
     * @return array
     */
    public function getInvalidatedMessages()
    {
        $messages = array();
        $languages = $this->languageFile ? require $this->languageFile : array();
        
        foreach ($this->invalidatedRules as $field => $rules) {
            foreach ($rules as $rule) {
                // Custom message
                if (isset($this->messages[$field])) {
                    if (is_string($this->messages[$field])) {
                        $messages[$field][$rule] = $this->messages[$field];
                    } elseif (isset($this->messages[$field][$rule])) {
                        $messages[$field][$rule] = $this->messages[$field][$rule];
                    // Get message from rule validator
                    } else {
                        $messages[$field][$rule] = isset($languages[$rule]) ? $languages[$rule] : $this->ruleValidators[$field][$rule]->getMessage();
                    }
                // Get message from rule validator
                } else {
                    $messages[$field][$rule] = isset($languages[$rule]) ? $languages[$rule] : $this->ruleValidators[$field][$rule]->getMessage();
                }
            }
        }
        
        return $messages;
    }
    
    /**
     * Set language code
     * 
     * @param string $code The language code
     * @return \Widget\Validator\Validator
     * @throws \InvalidArgumentException When language file not found
     */
    public function setLanguage($code)
    {
        $file = __DIR__ . '/../Resource/i18n/validator/' . $code . '.php';
        if (!is_file($file)) {
            throw new \InvalidArgumentException(sprintf('Validator language code "%s" is not available', $code));
        } else {
            $this->languageFile = $file;
            $this->language = $code;
            return $this;
        }
    }
}
