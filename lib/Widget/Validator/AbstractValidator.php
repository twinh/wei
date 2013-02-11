<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget\Validator;

use Widget\WidgetProvider;

abstract class AbstractValidator extends WidgetProvider implements ValidatorInterface
{
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
        $messages = array();
        foreach ($this->errors as $name => $vars) {
            $messages[$name] = $this->getErrorMessage($vars[0], $vars[1]);            
        }
        return $messages;
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
     * Returns error message
     * 
     * @param string $name
     * @param string $message
     * @return string
     * @throws \UnexpectedValueException
     */
    public function getErrorMessage($message, $vars = array())
    {
        if (!$message) {
            $message = $this->message;
        }
        
        if ($vars) {
            if (!isset($vars['name'])) {
                $vars['name'] = $this->name;
            }
            $keys = array_keys($vars);
            array_walk($keys, function(&$key) {
                $key = '%' . $key  . '%';
            });
            return str_replace($keys, $vars, $message);
        } else {
            return $message;
        }
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
     * @param string $name
     * @param array $vars
     */
    protected function addError($name, $vars = array(), $customMessage = null)
    {
        $this->errors[$name] = array(
            $customMessage ?: $this->{$name . 'Message'}, 
            $vars
        );
    }
}
