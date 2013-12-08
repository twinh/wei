<?php
/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei\Validator;

use Wei\Base;

/**
 * The base class of validator
 *
 * @author      Twin Huang <twinhuang@qq.com>
 * @method      string t(string $message, array $parameters = array()) Translates a message
 * @property    \Wei\T $t The translator wei
 */
abstract class BaseValidator extends Base
{
    /**
     * The message added when the input required a stringify value
     *
     * @var string
     */
    protected $notStringMessage = '%name% must be a string';

    /**
     * The common message for negative validator
     *
     * @var string
     */
    protected $negativeMessage = '%name% is not valid';

    /**
     * The name display in error message
     *
     * @var string
     */
    protected $name = 'This value';

    /**
     * The summary message that would overwrite all $this->xxxMessage messages
     * when called addError, which MAY useful when you want to set message for
     * the validator
     *
     * @var string
     * @internal
     */
    protected $message;

    /**
     * Whether it's a negative validator, for example, notDigit is digit's
     * negative validator. The negative validator will returns $this->negativeMessage
     * as the error message currently
     *
     * @var bool
     */
    protected $negative = false;

    /**
     * The error definition
     *
     * @var array
     */
    protected $errors = array();

    /**
     * The validate wei, available when this rule validator is invoked from validate wei
     *
     * @var \Wei\Validate
     */
    protected $validator;

    /**
     * An array contains the validator original property values
     *
     * @var array
     * @internal
     */
    protected $backup = array();

    /**
     * The array to store previous and current called parameters from __invoke
     *
     * @var array
     * @internal
     */
    protected $store = array(array());

    /**
     * Validate the input value
     *
     * @param mixed $input
     * @return bool
     */
    public function __invoke($input)
    {
        return $this->isValid($input);
    }

    /**
     * {@inheritdoc}
     */
    public function isValid($input)
    {
        // Clean previous status
        $this->reset();
        return $this->negative xor $this->doValidate($input);
    }

    /**
     * Validate the input value (ignore the $negative property)
     *
     * @param mixed $input The input to be validated
     * @return boolean
     */
    abstract protected function doValidate($input);

    /**
     * Returns the error messages
     *
     * @param string $name The name display in error message
     * @return array
     * @throws \UnexpectedValueException When message contains unknown parameter
     */
    public function getMessages($name = null)
    {
        !$name && $name = $this->name;
        $this->negative && $this->addError('negative');

        $this->loadTranslationMessages();

        $messages = array();
        foreach ($this->errors as $optionName => $message) {
            preg_match_all('/\%(.+?)\%/', $message, $matches);
            $parameters = array();
            foreach ($matches[1] as $match) {
                if ('name' == $match) {
                    $parameters['%name%'] = $this->t($name);
                } else {
                    if (!property_exists($this, $match)) {
                        throw new \UnexpectedValueException(sprintf('Unknown parameter "%%%s%%" in message "%s"', $match, $message));
                    }
                    $parameters['%' . $match . '%'] = is_array($this->$match) ?
                        implode(', ', $this->$match) : $this->$match;
                }
            }
            $messages[$optionName] = $this->t($message, $parameters);
        }
        return $messages;
    }

    /**
     * Returns error message string
     *
     * @param string $separator The string to connect messages
     * @param string $name The name display in error message
     * @return string
     */
    public function getJoinedMessage($separator = "\n", $name = null)
    {
        return implode($separator, $this->getMessages($name));
    }

    /**
     * Returns the first error message
     *
     * @param string $name
     * @return string
     */
    public function getFirstMessage($name = null)
    {
        return current($this->getMessages($name));
    }

    /**
     * Sets the specified messages
     *
     * @param array $messages
     * @return BaseValidator
     */
    public function setMessages(array $messages)
    {
        foreach ($messages as $name => $message) {
            $this->{$name . 'Message'} = $message;
        }
        return $this;
    }

    /**
     * Returns message name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets message name
     *
     * @param string $name
     * @return BaseValidator
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Returns error definition
     *
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Returns whether the error defined
     *
     * @param string $name
     * @return bool
     */
    public function hasError($name)
    {
        return isset($this->errors[$name]);
    }

    /**
     * Adds error definition
     *
     * @param string $name The name of error
     * @param string $customMessage The custom error message
     */
    protected function addError($name, $customMessage = null)
    {
        $this->errors[$name] = $customMessage ?: $this->message ?: $this->{$name . 'Message'};
    }

    /**
     * Loads the validator translation messages
     */
    protected function loadTranslationMessages()
    {
        $this->t->loadFromFile(dirname(__DIR__) . '/Resource/i18n/%s/validator.php');
    }

    /**
     * Checks if the input value could be convert to string
     *
     * @param mixed $input
     * @return bool
     */
    protected function isString($input)
    {
        return is_scalar($input) || is_null($input) || (is_object($input) && method_exists($input, '__toString'));
    }

    /**
     * Set property value
     *
     * @param string|array $name The name of property
     * @param mixed $value The value of property
     * @internal This method should be use to set __invoke arguments only
     */
    protected function storeOption($name, $value = null)
    {
        // Handle array
        if (is_array($name)) {
            foreach ($name as $key => $value) {
                $this->storeOption($key, $value);
            }
            return;
        }

        if (property_exists($this, $name)) {
            if (!array_key_exists($name, $this->backup)) {
                $this->backup[$name] = $this->$name;
            }
            $this->store[count($this->store) - 1][$name] = $value;
        }
        $this->setOption($name, $value);
    }

    /**
     * Reset validator status
     *
     * @internal
     */
    protected function reset()
    {
        $this->errors = array();

        if (count($this->store) >= 2) {
            $last = end($this->store);
            foreach ($this->backup as $name => $value) {
                if (!array_key_exists($name, $last)) {
                    $this->$name = $value;
                }
            }
            array_shift($this->store);
        }
        $this->store[] = array();
    }
}
