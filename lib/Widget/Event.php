<?php

/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2012 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget;

/**
 * Event
 *
 * @package     Widget
 * @author      Twin Huang <twinh@yahoo.cn>
 * @link        http://api.jquery.com/category/events/event-object/
 */
class Event extends WidgetProvider
{
    /**
     * The name of event
     *
     * @var string
     */
    protected $type;

    /**
     * The namespaces of event
     * 
     * @var array 
     */
    protected $namespaces = array();

    /**
     * Time stamp with microseconds when object constructed
     *
     * @var float
     */
    protected $timeStamp;
    
    /**
     * Whether prevent the default action or not
     * 
     * @var bool
     */
    protected $preventDefault = false;
    
    /**
     * Whether top triggering the next handler or nots
     * 
     * @var bool
     */
    protected $stopPropagation = false;

    /**
     * The last value returned by an event handler
     * 
     * @var mixed
     */
    protected $result;
    
    /**
     * The data accepted from the handler
     * 
     * @var array 
     */
    protected $data = array();
    
    /**
     * Constructor
     *
     * @param string $name
     */
    public function __construct(array $options = array())
    {
        $this->timeStamp = microtime(true);

        parent::__construct($options);
    }

    /**
     * Create a new event
     *
     * @return mixed
     */
    public function __invoke($type, array $namespaces = array())
    {
        return new static(array(
            'widget'        => $this->widget,
            'type'          => $type,
            'namespaces'    => $namespaces,
        ));
    }
    
    /**
     * Get the type of event
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set the type of event
     *
     * @param  string      $type
     * @return \Widget\Event
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }
    
    /**
     * Returns the event namespaces
     * 
     * @return array
     */
    public function getNamespaces()
    {
        return $this->namespaces;
    }
    
    /**
     * Set the namespace of event
     * 
     * @param array $namespaces
     * @return \Widget\Event
     */
    public function setNamespaces(array $namespaces)
    {
        $this->namespaces = $namespaces;
        
        return $this;
    }
    
    /**
     * Returns the event namespace
     * 
     * @return string
     */
    public function getNamespace()
    {
        return implode('.', $this->namespaces);
    }
    
    /**
     * Get the time stamp
     *
     * @return string
     */
    public function getTimeStamp()
    {
        return $this->timeStamp;
    }
    
    /**
     * @return \Widget\Event
     */
    public function preventDefault()
    {
        $this->preventDefault = true;
        
        return $this;
    }

    /**
     * @return bool
     */
    public function isDefaultPrevented()
    {
        return $this->preventDefault;
    }
    
    /**
     * @param mixed $result
     */
    public function setResult($result)
    {
        $this->result = $result;
        
        return $this;
    }
    
    /**
     * Returns the last result returnted by the handler
     * 
     * @return mixed
     */
    public function getResult()
    {
        return $this->result;
    }
    
    /**
     * @param mixed $data
     */
    public function setData($data = array())
    {
        $this->data = $data;
        
        return $this;
    }
    
    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }
    
    /**
     * @return \Widget\Event
     */
    public function stopPropagation()
    {
        $this->stopPropagation = true;
        
        return $this;
    }
    
    /**
     * @return bool
     */
    public function isPropagationStopped()
    {
        return $this->stopPropagation;
    }
}
