<?php

namespace Wei;

/**
 * A chaining validator
 *
 * @see Inspired by https://github.com/Respect/Validation/tree/1.1
 */
class V extends Base
{
    use RetTrait;

    /**
     * @var bool
     */
    protected static $createNewInstance = true;

    /**
     * @var Validate
     */
    protected $validator;

    /**
     * @var array
     */
    protected $options = [
        'data' => [],
        'rules' => [],
        'names' => [],
        'fields' => [],
    ];

    /**
     * @var string
     */
    protected $lastKey = '';

    /**
     * @var string
     */
    protected $lastRule;

    /**
     * Return a new instance of current service
     *
     * @return static
     */
    public static function new()
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return Wei::getContainer()->newInstance('v');
    }

    /**
     * Create a new validator
     *
     * @param array $options
     * @return $this
     */
    public function __invoke(array $options = [])
    {
        $validator = new self($options + get_object_vars($this));

        return $validator;
    }

    /**
     * Add rule for current field
     *
     * @param string $name
     * @param array $args
     * @return $this
     */
    public function __call($name, $args)
    {
        // TODO wei 提供接口判断是否可以调用为服务方法
        if (method_exists($this, $name)) {
            return $this->{$name}(...$args);
        }

        // Convert options [[option: xx]] to [option: xx]
        if (count($args) === 1 && is_array($args[0])) {
            $args = $args[0];
        }

        // Convert `$type($name, $label, ...$args)` to `key($name, $label)->addRule($type, $args)`
        if (substr($name, 0, 3) === 'not') {
            $rule = substr($name, 3);
        } else {
            $rule = $name;
        }
        $class = $this->wei->getClass('is' . ucfirst($rule));
        if (defined($class . '::BASIC_TYPE') && $class::BASIC_TYPE) {
            if (count($args) < 2) {
                throw new \InvalidArgumentException('Expected at least 2 arguments for type rule, but got ' . count($args));
            }
            [$keyName, $label] = $args;
            $args = array_slice($args, 2);
            $this->key($keyName, $label);
        }

        return $this->addRule($name, $args);
    }

    /**
     * Set rule message for current field
     *
     * @param string $ruleOrMessage
     * @param string|null $message
     * @return $this
     */
    public function message($ruleOrMessage, $message = null)
    {
        if (1 === func_num_args()) {
            $rule = $this->lastRule;
            $message = $ruleOrMessage;
        } else {
            $rule = $ruleOrMessage;
        }

        $this->options['messages'][$this->lastKey][$rule] = $message;

        return $this;
    }

    /**
     * Returns the \Wei\Validate object
     *
     * @param mixed $data
     * @return Validate
     */
    public function validate($data = null)
    {
        return $this->getValidator($data);
    }

    /**
     * Returns the validation result
     *
     * @param mixed $data
     * @return bool
     */
    public function isValid($data = null)
    {
        return $this->getValidator($data)->isValid();
    }

    /**
     * Validate the data and return the ret array
     *
     * @param mixed $data
     * @return Ret
     */
    public function check($data = null)
    {
        $validator = $this->getValidator($data);

        if ($validator->isValid()) {
            return $this->suc(['data' => $validator->getValidData()]);
        } else {
            return $this->err($validator->getFirstMessage());
        }
    }

    /**
     * Custom handler for required rule
     *
     * @param bool $required
     * @return $this
     */
    public function required($required = true)
    {
        return $this->addRule('required', $required);
    }

    /**
     * @return $this
     */
    public function optional()
    {
        return $this->required(false);
    }

    /**
     * Prepend allowEmpty rule to current key
     *
     * @return $this
     */
    public function allowEmpty()
    {
        return $this->prependRule(__FUNCTION__);
    }

    /**
     * Set data for validation
     *
     * @param mixed $data
     * @return $this
     */
    public function data($data)
    {
        if (!$data) {
            return $this;
        }

        // Validate without key
        if (!$this->lastKey) {
            $data = ['' => $data];
        }

        $this->options['data'] = $data;

        return $this;
    }

    /**
     * Add rule for current field
     *
     * @param string $name
     * @param mixed $args
     * @return $this
     */
    public function addRule($name, $args)
    {
        $this->options['rules'][$this->lastKey][$name] = $args;
        $this->lastRule = $name;

        return $this;
    }


    /**
     * Returns the validate options
     *
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Add name for current field
     *
     * @param string $label
     * @return $this
     * @svc
     */
    protected function label($label)
    {
        $this->options['names'][$this->lastKey] = $label;

        return $this;
    }

    /**
     * Add a new field
     *
     * @param string|array $name
     * @param string|null $label
     * @return $this
     * @svc
     */
    protected function key($name, $label = null)
    {
        if (is_array($name)) {
            $key = implode('.', $name);
            $this->options['fields'][$key] = $name;
            $name = $key;
        }

        $this->lastKey = $name;

        // Rest previous key's last rule
        $this->lastRule = null;

        if (!isset($this->options['rules'][$name])) {
            $this->options['rules'][$name] = [];
        }

        if (isset($label)) {
            $this->label($label);
        }

        return $this;
    }

    /**
     * Instance validate object
     *
     * @param mixed $data
     * @return Validate
     */
    protected function getValidator($data = null)
    {
        if (!$this->validator) {
            if ($data) {
                // Validate without key
                if ('' === $this->lastKey) {
                    $data = ['' => $data];
                }

                $this->options['data'] = $data;
            }

            $this->validator = $this->wei->validate($this->options);
        }

        return $this->validator;
    }

    /**
     * @param mixed $value
     * @param callable $callback
     * @param callable|null $default
     * @return $this
     * @svc
     */
    protected function when($value, $callback, callable $default = null)
    {
        if ($value) {
            $callback($this, $value);
        } elseif ($default) {
            $default($this, $value);
        }
        return $this;
    }

    /**
     * @param mixed $value
     * @param callable $callback
     * @param callable|null $default
     * @return $this
     * @svc
     */
    protected function unless($value, callable $callback, callable $default = null)
    {
        if (!$value) {
            $callback($this, $value);
        } elseif ($default) {
            $default($this, $value);
        }
        return $this;
    }

    /**
     * @return $this
     * @svc
     */
    protected function defaultOptional()
    {
        $this->options['defaultRequired'] = false;
        return $this;
    }

    /**
     * @return $this
     * @svc
     */
    protected function defaultRequired()
    {
        $this->options['defaultRequired'] = true;
        return $this;
    }

    /**
     * @param string $name
     * @param mixed $args
     * @return $this
     */
    private function prependRule(string $name, $args = [])
    {
        $this->options['rules'][$this->lastKey] = [$name => $args] + $this->options['rules'][$this->lastKey];
        $this->lastRule = $name;

        return $this;
    }
}
