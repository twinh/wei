<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Widget;

/**
 * ArrayWidget
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
abstract class ArrayWidget extends AbstractWidget implements \ArrayAccess, \Countable, \IteratorAggregate
{
    /**
     * The variable to store array
     *
     * @var array
     */
    protected $data = array();

    /**
     * Returns a parameter value
     *
     * @param  string $name    The parameter name
     * @param  mixed  $default The default parameter value if the parameter does not exist
     * @return mixed  The parameter value
     */
    public function __invoke($name, $default = null)
    {
        return isset($this->data[$name]) ? $this->data[$name] : $default;
    }

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
     * @return mixed
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
     * @return ArrayWidget
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
     * Returns the data keys
     *
     * @return array<integer|string>
     */
    public function keys()
    {
        return array_keys($this->data);
    }
}
