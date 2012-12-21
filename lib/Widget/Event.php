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
 * @see         http://api.jquery.com/category/events/event-object/
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
     * Time stamp with microseconds when object constructed
     *
     * @var float
     */
    protected $timeStamp;
    
    /**
     * @var bool
     */
    protected $preventDefault = false;

    /**
     *  The last value returned by an event handler
     * 
     * @var mixed
     */
    protected $result;
    
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
        $this->type = strtolower($type);

        return $this;
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
     * Create a new event
     *
     * @return mixed
     */
    public function __invoke($type)
    {
        return new static(array(
            'widget'    => $this->widget,
            'type'      => $type
        ));
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
     * @return type
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
    }
}
