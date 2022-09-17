<?php

namespace Wei;

use Wei\Ret\RetException;

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
     * @var string|array|null
     */
    protected $key;

    /**
     * @var string|null
     */
    protected $label;

    /**
     * The data to be validated
     *
     * @var mixed
     */
    protected $data;

    /**
     * The rules for validating the current key
     *
     * @var array
     */
    protected $rules = [];

    /**
     * @var array
     */
    protected $messages = [];

    /**
     * @var array<static>
     */
    protected $validators = [];

    /**
     * Whether all validators are required by default
     *
     * @var bool
     */
    protected $defaultRequired = true;

    /**
     * @var bool
     * @experimental
     */
    protected $defaultNotEmpty = false;

    /**
     * The rules that need to validate before other rules
     *
     * @var string[]
     * @internal
     */
    protected $prependRules = [
        'allowEmpty',
        'notEmpty',
    ];

    /**
     * Return a new instance of current service
     *
     * @return static
     */
    public static function new(): self
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return Wei::getContainer()->newInstance('v');
    }

    public static function __callStatic(string $method, array $args)
    {
        if (method_exists(static::class, $method)) {
            return parent::__callStatic($method, $args);
        }
        return static::new()->addRule($method, $args);
    }

    /**
     * Add rule for current field
     *
     * @param string $name
     * @param array $args
     * @return self|mixed
     */
    public function __call(string $name, array $args)
    {
        // Call service methods like: `V::defaultOptional`, `V::label()`
        if (method_exists($this, $name)) {
            return $this->{$name}(...$args);
        }

        // Important: Convert options from [[option: xx]] to [option: xx]
        if (1 === count($args) && is_array($args[0])) {
            $args = $args[0];
        }

        if ($this->hasValidatorConfig()) {
            return $this->addRule($name, $args);
        } else {
            return $this->addValidatorAndRule($name, $args);
        }
    }

    /**
     * @return array|string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @return string|null
     */
    public function getLabel(): ?string
    {
        return $this->label;
    }

    /**
     * Add a new validator for specified key
     *
     * @param string|array $key
     * @param string|null $label
     * @return $this
     */
    public function addValidator($key, string $label = null): self
    {
        return $this->validators[] = new static([
            'wei' => $this->wei,
            'key' => $key,
            'label' => $label,
            'defaultNotEmpty' => $this->defaultNotEmpty,
        ]);
    }

    /**
     * Alias of `addValidator`
     *
     * @param string|array $key
     * @param string|null $label
     * @return $this
     */
    public function key($key, string $label = null): self
    {
        return $this->addValidator($key, $label);
    }

    /**
     * Add a new validator to check the current data
     *
     * @return $this
     * @experimental implementation may be changed
     */
    public function self(): self
    {
        return $this->addValidator('');
    }

    /**
     * Set rule message for current validator
     *
     * @param string $ruleOrMessage
     * @param string|null $message
     * @return $this
     */
    public function message(string $ruleOrMessage, string $message = null): self
    {
        if (1 === func_num_args()) {
            $rule = $this->getLastKey($this->rules);
            $message = $ruleOrMessage;
        } else {
            $rule = $ruleOrMessage;
        }

        $this->messages[$rule] = $message;

        return $this;
    }

    /**
     * Custom handler for required rule
     *
     * @param bool $required
     * @return $this
     */
    public function required(bool $required = true): self
    {
        return $this->addRule('required', $required);
    }

    /**
     * @return $this
     */
    public function optional(): self
    {
        return $this->required(false);
    }

    /**
     * Add rule for current field
     *
     * @param string $name
     * @param mixed $options
     * @return $this
     */
    public function addRule(string $name, $options): self
    {
        if ($this->defaultNotEmpty && !$this->rules) {
            $this->rules['notEmpty'] = [];
        }

        if ('not' === substr($name, 0, 3)) {
            $rule = substr($name, 3);
        } else {
            $rule = $name;
        }
        if (!$this->wei->has('is' . ucfirst($rule))) {
            throw new \InvalidArgumentException(sprintf('Validator "%s" not found', $name));
        }

        // IMPORTANT
        if (in_array($name, $this->prependRules, true)) {
            $this->prependRule($name, $options);
        } else {
            $this->rules[$name] = $options;
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getRules(): array
    {
        return $this->rules;
    }

    /**
     * @return array
     */
    public function getMessages(): array
    {
        return $this->messages;
    }

    /**
     * Set the data to be validated
     *
     * @param mixed $data
     * @return $this
     */
    public function setData($data): self
    {
        $this->data = $data;
        return $this;
    }

    /**
     * Return the data to be validated
     *
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $value
     * @param callable $callback
     * @param callable|null $default
     * @return $this
     */
    public function when($value, callable $callback, callable $default = null): self
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
     */
    public function unless($value, callable $callback, callable $default = null): self
    {
        if (!$value) {
            $callback($this, $value);
        } elseif ($default) {
            $default($this, $value);
        }
        return $this;
    }

    /**
     * Returns the validation result
     *
     * @param mixed $data
     * @return bool
     */
    public function isValid($data = null): bool
    {
        return $this->validate(...func_get_args())->isValid();
    }

    /**
     * Validate the data and return the ret array
     *
     * @param mixed $data
     * @return Ret
     */
    public function check($data = null): Ret
    {
        $validator = $this->validate(...func_get_args());
        if ($validator->isValid()) {
            return $this->suc(['data' => $validator->getValidData()]);
        } else {
            return $this->err($validator->getFirstMessage());
        }
    }

    /**
     * Return validated data on success or throw exception on invalid
     *
     * @param mixed $data
     * @return mixed
     * @throws RetException
     */
    public function assert($data)
    {
        $ret = $this->check(...func_get_args());
        if ($ret->isErr()) {
            throw new RetException($ret);
        }
        return $ret->getData();
    }

    /**
     * Returns the \Wei\Validate object
     *
     * @param mixed $data
     * @return Validate
     * @internal will change in the future
     */
    public function validate($data = null): Validate
    {
        if (func_num_args()) {
            $this->setData($data);
        }

        $validator = null;
        if ($this->rules) {
            $key = $this->getKey();
            $validator = $this->wei->validate([
                'names' => [
                    $key => $this->getLabel(),
                ],
                'data' => $this->getData(),
                'rules' => [
                    $key => $this->getRules(),
                ],
            ]);
            if (!$validator->isValid()) {
                return $validator;
            }
        }

        if ($this->validators) {
            return $this->wei->validate($this->getOptions());
        }

        return $validator;
    }

    /**
     * Returns the validate options
     *
     * @return array
     * @internal will remove in the future
     */
    public function getOptions(): array
    {
        $rules = [];
        $names = [];
        $messages = [];
        $fields = [];
        foreach ($this->validators as $validator) {
            $key = $validator->getKey();
            if (is_array($key)) {
                $name = implode('.', $key);
                $fields[$name] = $key;
            } elseif (null === $key) {
                $name = $key;
                $fields[$name] = $key;
            } else {
                $name = $key;
            }
            $names[$name] = $validator->getLabel();
            $rules[$name] = $validator->getRules();
            $messages[$name] = $validator->getMessages();
        }
        return [
            'defaultRequired' => $this->defaultRequired,
            'data' => $this->data,
            'names' => $names,
            'rules' => $rules,
            'messages' => $messages,
            'fields' => $fields,
        ];
    }

    /**
     * Add label for current validator
     *
     * @param string $label
     * @return self
     * @svc
     */
    protected function label(string $label): self
    {
        $this->label = $label;
        return $this;
    }

    /**
     * @return $this
     * @svc
     */
    protected function defaultOptional(): self
    {
        $this->defaultRequired = false;
        return $this;
    }

    /**
     * @return $this
     * @svc
     */
    protected function defaultRequired(): self
    {
        $this->defaultRequired = true;
        return $this;
    }

    /**
     * @return $this
     * @svc
     * @experimental
     */
    protected function defaultNotEmpty(): self
    {
        $this->defaultNotEmpty = true;
        return $this;
    }

    /**
     * @return $this
     * @svc
     */
    protected function defaultAllowEmpty(): self
    {
        $this->defaultNotEmpty = false;
        return $this;
    }

    /**
     * @return bool
     * @internal
     */
    protected function hasValidatorConfig(): bool
    {
        return null !== $this->key || null !== $this->label || $this->rules;
    }

    /**
     * @param string $name
     * @param array $args
     * @return static
     */
    protected function addValidatorAndRule(string $name, array $args): self
    {
        if (count($args) < 2) {
            throw new \InvalidArgumentException(
                'Expected at least 2 arguments for rule, but got ' . count($args)
            );
        }

        // Convert `$type($key, $label, ...$args)` to `key($key, $label)->addRule($type, $args)`
        [$key, $label] = $args;
        $args = array_slice($args, 2);

        $validator = $this->addValidator($key, $label);
        $validator->addRule($name, $args);
        return $validator;
    }

    /**
     * @param string $name
     * @param mixed $args
     * @return $this
     */
    protected function prependRule(string $name, $args = []): self
    {
        $this->rules = [$name => $args] + $this->rules;
        return $this;
    }

    /**
     * @param array $array
     * @return string
     * @internal
     */
    protected function getLastKey(array $array): string
    {
        return key(array_slice($array, -1));
    }
}
