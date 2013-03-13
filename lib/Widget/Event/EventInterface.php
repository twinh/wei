<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget\Event;

/**
 * The base interface for event class
 *
 * @author      Twin Huang <twinh@yahoo.cn>
 */
interface EventInterface
{
    /**
     * Returns the type of event
     * 
     * @param bool $full Whether return type or type with with namespace 
     */
    public function getType($full = false);
    
    /**
     * Set the type of event
     *
     * @param  string      $type
     */
    public function setType($type);
    
    /**
     * Returns the namespaces of event
     */
    public function getNamespaces();
    
    /**
     * Set the namespaces of event
     *
     * @param array $namespaces
     */
    public function setNamespaces(array $namespaces);
    
    /**
     * Returns the event namespace
     */
    public function getNamespace();
    
    /**
     * Set a flag to prevent the default action
     */
    public function preventDefault();
    
    /**
     * Whether prevent the default action of event or not
     */
    public function isDefaultPrevented();
    
    /**
     * Sets the event result
     * 
     * @param mixed $result
     */
    public function setResult($result);
    
    /**
     * Returns the last value returned by the event handler
     */
    public function getResult();
    
    /**
     * Set a flag to stop trigger the next handler
     */
    public function stopPropagation();
    
    /**
     * Whether to trigger the next handler or not
     */
    public function isPropagationStopped();
}