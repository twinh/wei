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
 * @todo        message
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
     * The validated fields
     *
     * @var array
     */
    protected $validatedFields = array();

    /**
     * The invalidated fields
     *
     * @var array
     */
    protected $invalidatedFields = array();

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
                // Prepare parameters for validte widget
                if (!is_bool($params)) {
                    $params = (array) $params;
                }

                // The current rule validate result
                $result = $this->is->validateOne($rule, $data, $params);

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
        if (!isset($this->validatedRules[$field])) {
            $this->validatedFields[] = $field;
            $this->validatedRules[$field] = array();
        }

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
        if (!isset($this->invalidatedRules[$field])) {
            $this->invalidatedFields[] = $field;
            $this->invalidatedRules[$field] = array();
        }

        $this->invalidatedRules[$field][] = $rule;

        return $this;
    }

    /**
     * Check if field validted
     *
     * @param string $field
     * @return bool
     */
    public function isFieldValidated($field)
    {
        return !in_array($field, $this->invalidatedFields);
    }

    /**
     * Check if field invalidated
     *
     * @param string $field
     * @return bool
     */
    public function isFieldInvalidted($field)
    {
        return in_array($field, $this->invalidatedFields);
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
     * Get validate field data
     *
     * @param string $field The validate field
     * @return mixed
     */
    public function getFieldData($field)
    {
        return isset($this->data[$field]) ? $this->data[$field] : null;
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
}
