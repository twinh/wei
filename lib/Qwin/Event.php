<?php
/**
 * Qwin Framework
 *
 * @copyright   Copyright (c) 2008-2012 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Qwin;

/**
 * Event
 *
 * @package     Qwin
 * @author      Twin Huang <twinh@yahoo.cn>
 * @see         http://api.jquery.com/category/events/event-object/
 * @todo        add default event shut_down error ...
 */
class Event
{
    /**
     * The name of event
     *
     * @var string
     */
    protected $name;

    /**
     * Time stamp with microseconds when object constructed
     *
     * @var float
     */
    protected $timeStamp;

    /**
     * Creat a new event object
     *
     * @param string $name
     */
    public function __construct($name)
    {
        $this->setName($name);
        $this->timeStamp = microtime(true);
    }

    /**
     * Get the name of event
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set event name
     *
     * @param  string      $name
     * @return \Qwin\Event
     */
    public function setName($name)
    {
        $this->name = strtolower($name);

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
     * Trigger the event
     *
     * @return mixed
     */
    public function __invoke(array$args = array())
    {
        return $this->eventManager->__invoke($this, $args);
    }
}
