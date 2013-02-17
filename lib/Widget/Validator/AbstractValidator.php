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
 * The base class of validator
 * 
 * @package     Widget
 * @author      Twin Huang <twinh@yahoo.cn>
 * @method string t(string $message, array $parameters) Translates a message
 * @property \Widget\T $t The translator widget
 */
abstract class AbstractValidator extends WidgetProvider implements ValidatorInterface
{
    protected $notStringMessage = '%name% must be a string';
    
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
     * Whether it's the opposite validator, for examole, notDigit is digit's 
     * opposite validator. The opposite validator will returns $this->notMessage 
     * as the error message currently
     * 
     * @var string
     */
    protected $opposite = false;
    
    protected static $translationMessagesLoaded;
    
    /**
     * {@inheritdoc}
     */
    public function isValid($input)
    {
        return $this->__invoke($input);
    }
    
    /**
     * {@inheritdoc}
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
                        throw new \InvalidArgumentException(sprintf('Unkonwn parameter "%%%s%%" in message "%s"', $match, $message));
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
        if (is_array($customMessage)) {
            throw new \Widget\Exception('改啦');
        }
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
