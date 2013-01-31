<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget;

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
     * The rule validator instances
     * 
     * @var array<\Widget\Validator\AbstractRule>
     */
    protected $ruleValidators = array();

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
        
        // Initialize the validation result to true
        $this->result = true;

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

            // Start validation
            foreach ($rules as $rule => $params) {
                // Process simple array rule, eg 'username' => ['required', 'email']
                if (is_int($rule)) {
                    $rule = $params;
                    if (is_string($params)) {
                        $params = true;
                    }
                }
                
                // The current rule validation result
                $result = $this->is->validateOne($rule, $data, $params);

                if (is_object($rule)) {
                    $rule = get_class($rule);
                }
                
                // Record the rule validators
                $this->ruleValidators[$field][$rule] = $this->is->getLastRuleValidator();
                
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
    public function getInvalidRules($field)
    {
        return isset($this->invalidRules[$field]) ? $this->invalidRules[$field] : array();
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
     * Returns the validation result
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
     * @param array|Traversable $data
     * @return \Widget\Validator
     */
    public function setData($data)
    {
        if (!is_array($data) && !$data instanceof \Traversable) {
            throw new UnexpectedTypeException($data, 'array or Traversable');
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
        return isset($this->data[$field]) ? $this->data[$field] : null;
    }
    
    /**
     * Sets data for validation field
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
     * Returns invalid messages
     * 
     * @return array
     */
    public function getInvalidMessages()
    {
        $messages = array();
        $languages = $this->languageFile ? require $this->languageFile : array();
        
        foreach ($this->invalidRules as $field => $rules) {
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
     * @return \Widget\Validator
     * @throws \InvalidArgumentException When language file not found
     */
    public function setLanguage($code)
    {
        $file = __DIR__ . '/Resource/i18n/validator/' . $code . '.php';
        if (!is_file($file)) {
            throw new \InvalidArgumentException(sprintf('Validator language code "%s" is not available', $code));
        } else {
            $this->languageFile = $file;
            $this->language = $code;
            return $this;
        }
    }
    
    /**
     * Returns the language code
     * 
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
    }
}
