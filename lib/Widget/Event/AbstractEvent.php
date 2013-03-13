<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget\Event;

use Widget\AbstractWidget;

/**
 * A simple implementation of \Widget\Event\EventInterface
 *
 * @author      Twin Huang <twinh@yahoo.cn>
 */
abstract class AbstractEvent extends AbstractWidget implements EventInterface
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
     * Whether prevent the default action of event or not
     *
     * @var bool
     */
    protected $preventDefault = false;

    /**
     * Whether to trigger the next handler or not
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
     * @param array $options
     */
    public function __construct(array $options = array())
    {
        $this->timeStamp = microtime(true);

        parent::__construct($options);
    }

    /**
     * Returns the type of event
     * 
     * @param bool $full Whether return type or type with with namespace 
     * @return string
     */
    public function getType($full = false)
    {
        if ($full && $this->namespaces) {
            return $this->type . '.' . $this->getNamespace();
        } else {
            return $this->type;
        }
    }

    /**
     * Set the type of event
     *
     * @param  string      $type
     * @return \Widget\Event\Event
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Returns the namespaces of event
     *
     * @return array
     */
    public function getNamespaces()
    {
        return $this->namespaces;
    }

    /**
     * Set the namespaces of event
     *
     * @param array $namespaces
     * @return \Widget\Event\Event
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
     * Returns the time stamp when event constructed
     *
     * @return string|double
     */
    public function getTimeStamp()
    {
        return $this->timeStamp;
    }

    /**
     * Set a flag to prevent the default action
     * 
     * @return \Widget\Event\Event
     */
    public function preventDefault()
    {
        $this->preventDefault = true;

        return $this;
    }

    /**
     * Whether prevent the default action of event or not
     * 
     * @return bool
     */
    public function isDefaultPrevented()
    {
        return $this->preventDefault;
    }

    /**
     * Sets the event result
     * 
     * @param mixed $result
     */
    public function setResult($result)
    {
        $this->result = $result;

        return $this;
    }

    /**
     * Returns the last result returnted by the event handler
     *
     * @return mixed
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * Set the event data
     * 
     * @param array $data
     */
    public function setData($data = array())
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Returns the event data
     * 
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Set a flag to stop trigger the next handler
     * 
     * @return \Widget\Event\Event
     */
    public function stopPropagation()
    {
        $this->stopPropagation = true;

        return $this;
    }

    /**
     * Whether to trigger the next handler or not
     * 
     * @return bool
     */
    public function isPropagationStopped()
    {
        return $this->stopPropagation;
    }
}