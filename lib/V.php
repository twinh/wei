<?php

namespace Wei;

use Miaoxing\Plugin\BaseService;

/**
 * A chaining validator
 *
 * Data type and composition
 * @method $this alnum() Check if the input contains letters (a-z) and digits (0-9)
 * @method $this notAlnum()
 * @method $this alpha() Check if the input contains only letters (a-z)
 * @method $this notAlpha()
 * @method $this blank() Check if the input is blank
 * @method $this contains($search, $regex = false) Check if the input is contains the specified string or pattern
 * @method $this notContains($search, $regex = false)
 * @method $this decimal() Check if the input is decimal
 * @method $this notDecimal()
 * @method $this digit() Check if the input contains only digits (0-9)
 * @method $this notDigit()
 * @method $this divisibleBy($divisor) Check if the input could be divisible by specified divisor
 * @method $this notDivisibleBy($divisor)
 * @method $this doubleByte() Check if the input contains only double characters
 * @method $this notDoubleByte()
 * @method $this present() Check if the input is empty
 * @method $this notPresent()
 * @method $this endsWith($findMe, $case = false) Check if the input is ends with specified string
 * @method $this notEndsWith($findMe, $case = false)
 * @method $this in(array $array, $strict = false) Check if the input is in specified array
 * @method $this notIn(array $array, $strict = false)
 * @method $this lowercase() Check if the input is lowercase
 * @method $this notLowercase()
 * @method $this luhn() Check if the input is valid by the Luhn algorithm
 * @method $this notLuhn()
 * @method $this naturalNumber() Check if the input is a natural number (integer that greater than or equals 0)
 * @method $this notNaturalNumber()
 * @method $this null() Check if the input is null
 * @method $this notNull()
 * @method $this number() Check if the input is number
 * @method $this notNumber()
 * @method $this positiveInteger() Check if the input is a positive integer (integer that greater than 0)
 * @method $this notPositiveInteger()
 * @method $this regex($pattern) Check if the input is valid by specified regular expression
 * @method $this notRegex($pattern)
 * @method $this startsWith($findMe, $case = false) Check if the input is starts with specified string
 * @method $this notStartsWith($findMe, $case = false)
 * @method $this type($type) Check if the type of input is equals specified type name
 * @method $this notType($type)
 * @method $this uppercase() Check if the input is uppercase
 * @method $this notUppercase()
 *
 * Length
 * @method $this length($length, $min = null, $max = null) Check if the length (or size) is equals or in specified range
 * @method $this notLength($length, $min = null, $max = null)
 * @method $this charLength($length) Check if the characters length of input is equals specified length
 * @method $this notCharLength($length)
 * @method $this minLength($min) Check if the length (or size) of input is greater than specified length
 * @method $this notMinLength($min)
 * @method $this maxLength($max) Check if the length (or size) of input is lower than specified length
 * @method $this notMaxLength($max)
 *
 * Comparison
 * @method $this equalTo($value) Check if the input is equals to (==) the specified value
 * @method $this notEqualTo($value)
 * @method $this identicalTo($value) Check if the input is equals to (==) the specified value
 * @method $this notIdenticalTo($value)
 * @method $this greaterThan($value) Check if the input is greater than (>=) the specified value
 * @method $this notGreaterThan($value)
 * @method $this greaterThanOrEqual($value) Check if the input is greater than or equal to (>=) the specified value
 * @method $this notGreaterThanOrEqual($value)
 * @method $this lessThan($value) Check if the input is less than (<) the specified value
 * @method $this notLessThan($value)
 * @method $this lessThanOrEqual($value) Check if the input is less than or equal to (<=) the specified value
 * @method $this notLessThanOrEqual($value)
 * @method $this between($min, $max) Check if the input is between the specified minimum and maximum value
 * @method $this notBetween($min, $max)
 *
 * Date and time
 * @method $this date($format = 'Y-m-d') Check if the input is a valid date
 * @method $this notate($format = 'Y-m-d')
 * @method $this dateTime($format = null) Check if the input is a valid datetime
 * @method $this notDateTime($format = null)
 * @method $this time($format = 'H:i:s') Check if the input is a valid time
 * @method $this notTime($format = 'H:i:s')
 *
 * File and directory
 * @method $this dir() Check if the input is existing directory
 * @method $this notDir()
 * @method $this exists() Check if the input is existing file or directory
 * @method $this notExists()
 * @method $this file(array $options) Check if the input is valid file
 * @method $this notFile(array $options)
 * @method $this image(array $options = array()) Check if the input is valid image
 * @method $this notImage(array $options = array())
 *
 * Network
 * @method $this email() Check if the input is valid email address
 * @method $this notEmail()
 * @method $this ip(array $options = array()) Check if the input is valid IP address
 * @method $this notIp(array $options = array())
 * @method $this tld() Check if the input is a valid top-level domain
 * @method $this notTld()
 * @method $this url(array $options = array()) Check if the input is valid URL address
 * @method $this notUrl(array $options = array())
 * @method $this uuid() Check if the input is valid UUID(v4)
 * @method $this notUuid()
 *
 * Region
 * @method $this creditCard($type = null) Check if the input is valid credit card number
 * @method $this notCreditCard($type = null)
 * @method $this phone() Check if the input is valid phone number, contains only digit, +, - and spaces
 * @method $this notPhone()
 * @method $this chinese() Check if the input contains only Chinese characters
 * @method $this notChinese()
 * @method $this idCardCn() Check if the input is valid Chinese identity card
 * @method $this notIdCardCn()
 * @method $this idCardHk() Check if the input is valid Hong Kong identity card
 * @method $this notIdCardHk()
 * @method $this idCardMo() Check if the input is valid Macau identity card
 * @method $this notIdCardMo()
 * @method $this idCardTw() Check if the input is valid Taiwan identity card
 * @method $this notIdCardTw()
 * @method $this phoneCn() Check if the input is valid Chinese phone number
 * @method $this notPhoneCn()
 * @method $this plateNumberCn() Check if the input is valid Chinese plate number
 * @method $this notPlateNumberCn()
 * @method $this postcodeCn() Check if the input is valid Chinese postcode
 * @method $this notPostcodeCn()
 * @method $this qQ() Check if the input is valid QQ number
 * @method $this notQQ()
 * @method $this mobileCn() Check if the input is valid Chinese mobile number
 * @method $this notMobileCn()
 *
 * Group
 * @method $this allOf(array $rules) Check if the input is valid by all of the rules
 * @method $this notAllOf(array $rules)
 * @method $this noneOf(array $rules) Check if the input is NOT valid by all of specified rules
 * @method $this notNoneOf(array $rules)
 * @method $this oneOf(array $rules) Check if the input is valid by any of the rules
 * @method $this notOneOf(array $rules)
 * @method $this someOf(array $rules, $atLeast) Check if the input is valid by specified number of the rules
 * @method $this notSomeOf(array $rules, $atLeast)
 *
 * Others
 * @method $this recordExists($table, $field = 'id') Check if the input is existing table record
 * @method $this notRecordExists($table, $field = 'id')
 * @method $this all(array $rules) Check if all of the element in the input is valid by all specified rules
 * @method $this notAll(array $rules)
 * @method $this callback(\Closure $fn, $message = null) Check if the input is valid by specified callback
 * @method $this notCallback(\Closure $fn, $message = null)
 * @method $this color() Check if the input is valid Hex color
 * @method $this notColor()
 * @method $this password(array $options = array()) Check if the input password is secure enough
 * @method $this notPassword(array $options = array())
 *
 * @see Inspired by https://github.com/Respect/Validation/tree/1.1
 */
class V extends BaseService
{
    use RetTrait;

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
     * Add a new field
     *
     * @param string $name
     * @param string|null $label
     * @return $this
     * @svc
     */
    protected function key($name, $label = null)
    {
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
     * Add name for current field
     *
     * @param string $label
     * @return $this
     */
    public function label($label)
    {
        $this->options['names'][$this->lastKey] = $label;

        return $this;
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
     * @return \Miaoxing\Plugin\Service\Ret
     */
    public function check($data = null)
    {
        $validator = $this->getValidator($data);

        if ($validator->isValid()) {
            return $this->suc();
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
            return $this->$name(...$args);
        }
        return $this->addRule($name, $args);
    }
}
