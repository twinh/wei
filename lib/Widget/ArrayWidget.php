<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2012 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget;

/**
 * ArrayWidget
 *
 * @package     Widget
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class ArrayWidget extends WidgetProvider implements \ArrayAccess, \Countable, \IteratorAggregate
{
    /**
     * The variable to store array
     *
     * @var array
     */
    protected $data = array();

    /**
     * Check if the offset exists
     *
     * @param  string $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->data[$offset]);
    }

    /**
     * Get the offset value
     *
     * @param  string $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return isset($this->data[$offset]) ? $this->data[$offset] : null;
    }

    /**
     * Set the offset value
     *
     * @param string $offset
     * @param mixed  $value
     */
    public function offsetSet($offset, $value)
    {
        return $this->data[$offset] = $value;
    }

    /**
     * Unset the offset
     *
     * @param string $offset
     */
    public function offsetUnset($offset)
    {
        unset($this->data[$offset]);
    }

    /**
     * Merge data from array
     *
     * @param  array            $array
     * @return Widget_ArrayWidget
     */
    public function fromArray(array $array = array())
    {
        $this->data = $array;

        return $this;
    }

    /**
     * Return the source array of the object
     *
     * @return array
     */
    public function toArray()
    {
        return $this->data;
    }

    /**
     * Return the length of data
     *
     * @return int the length of data
     */
    public function count()
    {
        return count($this->data);
    }

    /**
     * Returns an iterator for data
     *
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->data);
    }
    
    /**
     * Returns the data keys.
     * 
     * @return array
     */
    public function keys()
    {
        return array_keys($this->data);
    }
}
