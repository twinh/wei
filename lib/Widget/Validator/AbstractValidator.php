<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget\Validator;

use Widget\AbstractWidget;
use Widget\Exception\UnexpectedValueException;

/**
 * The base class of validator
 * 
 * @author      Twin Huang <twinh@yahoo.cn>
 * @method string t(string $message, array $parameters = array()) Translates a message
 * @property \Widget\T $t The translator widget
 */
abstract class AbstractValidator extends AbstractWidget implements ValidatorInterface
{
    protected $notStringMessage = '%name% must be a string';
    
    /**
     * The common message for opposite validator
     * 
     * @var string
     */
    protected $notMessage = '%name% is not valid';
    
    /**
     * The message name
     * 
     * @var string
     */
    protected $name = 'This value';
    
    /**
     * The error definition
     * 
     * @var array
     */
    protected $errors = array();
    
    /**
     * The summary message that would overwrite all $this->xxxMessage messages
     * when called addError, which MAY useful when you want to set message for 
     * the validaor
     * 
     * @var string
     * @internal
     */
    protected $message;
    
    /**
     * Whether it's a opposite validator, for examole, notDigit is digit's 
     * opposite validator. The opposite validator will returns $this->notMessage 
     * as the error message currently
     * 
     * @var string
     */
    protected $opposite = false;
    
    /**
     * The array constains the validator original property values
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
     * Whether the translation widget has loaded the validator messages
     * 
     * @var bool
     */
    protected static $translationMessagesLoaded;
     
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
         
        return $this->opposite xor $this->validate($input);
    }
    
    /**
     * Validate the input value (ignore the $opposite property)
     * 
     * @param type $input
     * @return boolean
     */
    abstract protected function validate($input);
    
    /**
     * Set property value
     * 
     * @param string $name The name of property
     * @param mixed $value The value of property
     * @return \Widget\Validator\AbstractValidator
     * @internal This method should be use to set __invoke arguments only
     */
    protected function setOption($name, $value)
    {
        if (property_exists($this, $name)) {
            if (!array_key_exists($name, $this->backup)) {
                $this->backup[$name] = $this->$name;
            }
            $this->store[count($this->store) - 1][$name] = $value;
        }
        
        $this->option($name, $value);
        
        return $this;
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
    
    /**
     * {@inheritdoc}
     * @throws \Widget\Exception\UnexpectedValueException When message contains unkonwn parameter
     */
    public function getMessages()
    {
        $this->loadTranslationMessages();
        
        if ($this->opposite) {
            $this->addError('not');
        }

        $messages = array();
        foreach ($this->errors as $name => $message) {
            preg_match_all('/\%(.+?)\%/', $message, $matches);
            $parameters = array();
            foreach ($matches[1] as $match) {
                if ('name' == $match) {
                    $parameters['%name%'] = $this->t($this->name);
                } else {
                    if (!property_exists($this, $match)) {
                        throw new UnexpectedValueException(sprintf('Unkonwn parameter "%%%s%%" in message "%s"', $match, $message));
                    }
                    $parameters['%' . $match . '%'] = is_array($this->$match) ? 
                        implode(', ', $this->$match) : $this->$match;;
                }
            }
            $messages[$name] = $this->t($message, $parameters);      
        }
        return $messages;
    }
    
    /**
     * Loads the validator translation messages
     * 
     * @todo better way?
     */
    protected function loadTranslationMessages()
    {
        if (!static::$translationMessagesLoaded) {
            $this->t->loadFromFile(dirname(__DIR__) . '/Resource/i18n/%s/validator.php');
            static::$translationMessagesLoaded = true;
        }
    }
    
    /**
     * Sets the specified messages
     * 
     * @param array $messages
     * @return \Widget\Validator\AbstractValidator
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
     * @return \Widget\Validator\AbstractValidator
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
     * Checks if the input value could be convert to string
     * 
     * @param mixed $input
     * @return bool
     */
    protected function isString($input)
    {
        return is_scalar($input) || is_null($input) || (is_object($input) && method_exists($input, '__toString'));
    }
}