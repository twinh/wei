<?php
/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2020 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

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
    protected $rules = [];

    /**
     * The data to be validated
     *
     * @var array
     */
    protected $data = [];

    /**
     * The invalid messages
     *
     * @var array
     */
    protected $messages = [];

    /**
     * The names for messages
     *
     * @var array
     */
    protected $names = [];

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
     * Whether all data is required by default
     *
     * @var bool
     */
    protected $defaultRequired = true;

    /**
     * The valid rules array, which use the field as key, and the rules as value
     *
     * @var string
     */
    protected $validRules = [];

    /**
     * The invalid rules array, which use the field as key, and the rules as value
     *
     * @var string
     */
    protected $invalidRules = [];

    /**
     * The validation result
     *
     * @var bool|null
     */
    protected $result;

    /**
     * The rule validator instances
     *
     * @var array<BaseValidator>
     */
    protected $ruleValidators = [];

    /**
     * The current validating field name
     *
     * @var string|array
     */
    protected $currentField;

    /**
     * The current validating rule name
     *
     * @var string
     */
    protected $currentRule;

    /**
     * An array contains data path and field
     *
     * eg: ['user.email' => ['user', 'email']]
     *
     * @var array
     */
    protected $fields = [];

    /**
     * Whether to ignore the remaining rules of current field
     *
     * @var bool
     */
    protected $skipNextRules = false;

    /**
     * The data that has been validated
     *
     * @var array
     */
    protected $validData = [];

    /**
     * Create a new validator and validate by specified options
     *
     * @param array $options
     * @return $this
     */
    public function __invoke(array $options = [])
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
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @todo split to small methods
     */
    public function valid($options = [])
    {
        $options && $this->setOption($options);

        // Initialize the validation result to be true
        $this->result = true;

        $this->beforeValidate && call_user_func($this->beforeValidate, $this, $this->wei);

        foreach ($this->rules as $path => $rules) {
            $this->currentField = $this->toField($path);
            $data = $this->getFieldData($this->currentField);

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
                $rules = [$rules => true];
            } elseif ($rules instanceof BaseValidator) {
                $rules = [$rules];
            } elseif (!is_array($rules)) {
                throw new \InvalidArgumentException(sprintf(
                    'Expected argument of type array, string or instance of Wei\BaseValidator, "%s" given',
                    is_object($rules) ? get_class($rules) : gettype($rules)
                ));
            }

            // Make sure the "required" rule at first
            if (!isset($rules['required'])) {
                $isRequired = $this->defaultRequired;
            } else {
                $isRequired = (bool) $rules['required'];
                unset($rules['required']);
            }
            $rules = ['required' => $isRequired] + $rules;

            // Start validation
            foreach ($rules as $rule => $params) {
                $this->currentRule = $rule;

                // Prepare property options for validator
                $props = $this->prepareProps($path, $rule);

                // The current rule validation result
                /** @var $validator BaseValidator */
                $validator = null;
                $result = $this->validateOne($rule, $data, $params, $validator, $props);

                if (is_object($params)) {
                    $rule = get_class($params);
                }

                // Record the rule validators
                $this->ruleValidators[$path][$rule] = $validator;

                // If any rule is invalid, the result would always be false in the whole validation flow
                if (false === $result) {
                    $this->result = false;
                }

                $isOptional = $validator instanceof IsRequired && $validator->isInvalid();
                if ($result && !$isOptional) {
                    $this->addValidData($this->currentField, $validator->getValidData());
                }

                // Record the valid/invalid rule
                $method = $result ? 'addValidRule' : 'addInvalidRule';
                $this->{$method}($this->currentField, $rule);

                // Trigger the ruleValid/ruleInvalid callback
                $fn = $result ? 'ruleValid' : 'ruleInvalid';
                if ($this->{$fn} && false === call_user_func($this->{$fn}, $rule, $path, $this, $this->wei)) {
                    return $this->result;
                }

                if ($result) {
                    // The field data is empty and optional, skip the remaining validation rules
                    if ($isOptional) {
                        break;
                    }
                } else {
                    // Break the validation flow when any field's rule is invalid
                    if ('required' === $rule || $this->breakRule || $this->skip) {
                        break;
                    }
                }

                if ($this->skipNextRules) {
                    $this->skipNextRules = false;
                    break;
                }
            }

            // Trigger the fieldValid/fieldInvalid callback
            $callback = $this->isFieldValid($path) ? 'fieldValid' : 'fieldInvalid';
            if ($this->{$callback} && false === call_user_func($this->{$callback}, $path, $this, $this->wei)) {
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
        $this->{$callback} && call_user_func($this->{$callback}, $this, $this->wei);

        return $this->result;
    }

    /**
     * Whether to ignore the remaining rules of current field
     *
     * @param bool $skipNextRules
     */
    public function skipNextRules($skipNextRules = true)
    {
        $this->skipNextRules = $skipNextRules;
    }

    /**
     * Add valid rule
     *
     * @param string|array $field The field name
     * @param string $rule The rule name
     * @return $this
     */
    public function addValidRule($field, $rule)
    {
        $this->validRules[$this->toPath($field)][] = $rule;
        return $this;
    }

    /**
     * Add invalid rule
     *
     * @param string|array $field The field name
     * @param string $rule The rule name
     * @return $this
     */
    public function addInvalidRule($field, $rule)
    {
        $this->invalidRules[$this->toPath($field)][] = $rule;
        return $this;
    }

    /**
     * Returns the valid fields
     *
     * @return array
     */
    public function getValidFields()
    {
        return array_map([$this, 'toField'], array_keys(array_diff_key($this->validRules, $this->invalidRules)));
    }

    /**
     * Returns the invalid fields
     *
     * @return array
     */
    public function getInvalidFields()
    {
        return array_map([$this, 'toField'], array_keys($this->invalidRules));
    }

    /**
     * Check if field is valid
     *
     * @param string|array $field
     * @return bool
     */
    public function isFieldValid($field)
    {
        return !in_array($field, $this->getInvalidFields(), true);
    }

    /**
     * Check if field is invalid
     *
     * @param string|array $field
     * @return bool
     */
    public function isFieldInvalid($field)
    {
        return in_array($field, $this->getInvalidFields(), true);
    }

    /**
     * Set field rules
     *
     * @param array $rules
     * @return $this
     */
    public function setRules(array $rules = null)
    {
        $this->rules = (array) $rules;
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
     * @param string|array $field
     * @return array
     */
    public function getFieldRules($field)
    {
        return $this->rules[$this->toPath($field)] ?? [];
    }

    /**
     * Get validation rule parameters
     *
     * @param string|array $field The validation field
     * @param string $rule The validation rule
     * @return array
     */
    public function getRuleParams($field, $rule)
    {
        return (array) ($this->rules[$this->toPath($field)][$rule] ?? []);
    }

    /**
     * Get valid rules by field
     *
     * @param string|array $field
     * @return array
     */
    public function getValidRules($field)
    {
        return $this->validRules[$this->toPath($field)] ?? [];
    }

    /**
     * Get invalid rules by field
     *
     * @param string|array $field
     * @return array
     */
    public function getInvalidRules($field = null)
    {
        return $field ?
            $this->invalidRules[$this->toPath($field)] ?? []
            : $this->invalidRules;
    }

    /**
     * Returns the validation result
     *
     * @return bool
     */
    public function isValid()
    {
        return null === $this->result ? $this->__invoke() : $this->result;
    }

    /**
     * Adds rule for specified field
     *
     * @param string|array $field The name of field
     * @param string $rule The name of rule
     * @param mixed $parameters The parameters for rule
     */
    public function addRule($field, $rule, $parameters)
    {
        $this->rules[$this->toPath($field)][$rule] = $parameters;
    }

    /**
     * Returns whether the validation rule exists in specified field
     *
     * @param string|array $field
     * @param string $rule
     * @return bool
     */
    public function hasRule($field, $rule)
    {
        return isset($this->rules[$this->toPath($field)][$rule]);
    }

    /**
     * Removes the rule in field
     *
     * @param string|array $field The name of field
     * @param string $rule The name of rule
     * @return bool
     */
    public function removeRule($field, $rule)
    {
        $path = $this->toPath($field);
        if (isset($this->rules[$path][$rule])) {
            unset($this->rules[$path][$rule]);
            return true;
        }
        return false;
    }

    /**
     * Removes the validate field
     *
     * @param string|array $field
     * @return bool
     */
    public function removeField($field)
    {
        $path = $this->toPath($field);
        if (isset($this->rules[$path])) {
            unset($this->rules[$path]);
            return true;
        }
        return false;
    }

    /**
     * Sets data for validation
     *
     * @param array|object $data
     * @return $this
     * @throws \InvalidArgumentException when argument type is not array or object
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
     * @param string|array $field The name of field
     * @return mixed
     */
    public function getFieldData($field)
    {
        return $this->getDataByPaths($this->data, (array) $field);
    }

    /**
     * Check if field exists in data
     *
     * @param string|array $field
     * @return bool
     */
    public function hasField($field)
    {
        if (is_array($field)) {
            $lastField = array_pop($field);
            $data = $this->getDataByPaths($this->data, $field);
        } else {
            $lastField = $field;
            $data = $this->data;
        }

        if (is_array($data)) {
            return array_key_exists($lastField, $data);
        } elseif ($data instanceof \ArrayAccess) {
            return $data->offsetExists($lastField);
        } elseif (property_exists($this->data, $lastField)) {
            return true;
        } elseif (method_exists($this->data, 'get' . $lastField)) {
            // @experimental Assume field exists
            return true;
        } else {
            return false;
        }
    }

    /**
     * Sets data for validation field
     *
     * NOTE: Do not support array fields yet.
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
            $this->data->{$field} = $data;
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
        $this->messages = (array) $messages;
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
        $messages = [];
        foreach ($this->invalidRules as $path => $rules) {
            foreach ($rules as $rule) {
                $messages[$path][$rule] = $this->ruleValidators[$path][$rule]->getMessages();
            }
        }
        return $messages;
    }

    /**
     * Returns invalid messages as a one-dimensional array
     *
     * Example:
     *
     * ```
     * [
     *    'field-required-required' => 'Field is required',
     *    'my.field2-required-required' => 'Field2 is required',
     * ]
     * ```
     *
     * @return array
     */
    public function getFlatMessages()
    {
        $messages = [];
        $detailMessages = $this->getDetailMessages();

        foreach ($detailMessages as $path => $rules) {
            foreach ($rules as $rule => $options) {
                foreach ($options as $name => $message) {
                    $messages[$path . '-' . $rule . '-' . $name] = $message;
                }
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
        $summaries = [];
        foreach ($messages as $path => $rules) {
            foreach ($rules as $options) {
                foreach ($options as $message) {
                    $summaries[$path][] = $message;
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
        $array = [];
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
     * @param string|array $field
     * @param string $rule
     * @return BaseValidator
     */
    public function getRuleValidator($field, $rule)
    {
        return $this->ruleValidators[$this->toPath($field)][$rule] ?? null;
    }

    /**
     * Sets field names
     *
     * @param array $names
     */
    public function setNames($names)
    {
        $this->names = (array) $names;
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
     * @param string|BaseValidator|int $rule
     * @param array|null $input
     * @param mixed $options
     * @param null &$validator
     * @param array $props
     * @return bool
     * @internal Do NOT use this method for it may be changed in the future
     */
    public function validateOne($rule, $input, $options = [], &$validator = null, $props = [])
    {
        // Process simple array rules, eg 'username' => ['required', 'email']
        if (is_int($rule)) {
            $rule = $options;
            if (is_string($options)) {
                $options = true;
            }
        }

        if ($rule instanceof BaseValidator) {
            $validator = $rule;
            return $rule($input);
        }

        $validator = $this->createRuleValidator($rule, $props);

        if (!is_array($options)) {
            $options = [$options];
        }

       // dd('xxx', $options, $input);

        if (is_int(key($options))) {
            array_unshift($options, $input);
            $result = call_user_func_array($validator, $options);
        } else {
            $validator->setOption($options);
            $result = $validator($input);
        }

        //dd('x12313', get_class($validator), $options, $result);


        return $result;
    }

    /**
     * Create a rule validator instance by specified rule name
     *
     * @param string $rule The name of rule validator
     * @param array $options The property options for rule validator
     * @return BaseValidator
     * @throws \InvalidArgumentException When validator not found
     */
    public function createRuleValidator($rule, array $options = [])
    {
        // Starts with "not", such as notDigit, notEqual
        if (0 === stripos($rule, 'not')) {
            $options['negative'] = true;
            $rule = substr($rule, 3);
        }

        $object = 'is' . ucfirst($rule);
        $class = $this->wei->getClass($object);
        if (!$class || !class_exists($class)) {
            throw new \InvalidArgumentException(sprintf('Validator "%s" not found', $rule));
        }

        $options = $options + ['wei' => $this->wei] + (array) $this->wei->getConfig('is' . ucfirst($rule));

        return new $class($options);
    }

    /**
     * Returns the current validating rule name
     *
     * @return string|array
     */
    public function getCurrentField()
    {
        return $this->currentField;
    }

    /**
     * Returns the current validating rule name
     *
     * @return string
     */
    public function getCurrentRule()
    {
        return $this->currentRule;
    }

    /**
     * Add valid data
     *
     * @param string|array $field
     * @param $data
     * @return $this
     */
    public function addValidData($field, $data)
    {
        $this->validData = $this->setDataByPaths($this->validData, $field, $data);
        return $this;
    }

    /**
     * Get the data that has been validated
     *
     * @return array
     */
    public function getValidData()
    {
        return $this->result ? $this->validData : [];
    }

    /**
     * Prepare name and messages property option for rule validator
     *
     * @param string $path
     * @param string $rule
     * @return array
     */
    protected function prepareProps($path, $rule)
    {
        $props = $messages = [];

        $props['validator'] = $this;

        // Prepare name for validator
        if (isset($this->names[$path])) {
            $props['name'] = $this->names[$path];
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
        if (isset($this->messages[$path][$rule]) && is_array($this->messages[$path])) {
            $messages = $this->messages[$path][$rule];
            // Case 1
        } elseif (isset($this->messages[$path]) && is_scalar($this->messages[$path])) {
            $messages = $this->messages[$path];
        }

        // Convert message to array for validator
        if (is_scalar($messages)) {
            $props['message'] = $messages;
        } elseif (is_array($messages)) {
            foreach ($messages as $name => $message) {
                $props[$name . 'Message'] = $message;
            }
        }

        return $props;
    }

    /**
     * Convert path to field, eg 'user.email' to ['user', 'email']
     *
     * @param string $path
     * @return string
     */
    private function toField(string $path)
    {
        return $this->fields[$path] ?? $path;
    }

    /**
     * Convert field to path, eg ['user', 'email'] to 'user.email'
     *
     * @param string|array $field
     * @return string
     */
    private function toPath($field)
    {
        if (is_array($field)) {
            return array_search($field, $this->fields, true) ?: implode('.', $field);
        }
        return $field;
    }

    /**
     * @param mixed $data
     * @param array $paths
     * @return mixed
     */
    private function getDataByPaths($data, array $paths)
    {
        foreach ($paths as $path) {
            // $data could only be array or object, which has been checked by $this->setData
            if ((is_array($data) && array_key_exists($path, $data))
                || ($data instanceof \ArrayAccess && $data->offsetExists($path))
            ) {
                $data = $data[$path];
            } elseif ($data instanceof \Closure) {
                // Call isset($closure->{$field}) will throw "Error : Closure object cannot have properties"
                return null;
            } elseif (isset($data->{$path})) {
                $data = $data->{$path};
            } elseif (method_exists($data, 'get' . $path)) {
                $data = $data->{'get' . $path}();
            } else {
                return null;
            }
        }
        return $data;
    }

    /**
     * @param array $data
     * @param string|array $paths
     * @param mixed $value
     * @return array
     */
    private function setDataByPaths(array $data, $paths, $value): array
    {
        $next = &$data;
        $paths = (array) $paths;
        $lastPath = array_pop($paths);
        foreach ($paths as $path) {
            if (!array_key_exists($path, $next)) {
                $next[$path] = [];
            }
            $next = &$next[$path];
        }
        $next[$lastPath] = $value;
        return $data;
    }
}
