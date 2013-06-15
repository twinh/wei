<?php
/**
 * Widget Framework
 *
 * @copyright   Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Widget\Stdlib;

use Widget\AbstractWidget;

/**
 * The basic class for request widget, like get, post etc
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
abstract class Parameter extends AbstractWidget implements \ArrayAccess, \Countable, \IteratorAggregate
{
    /**
     * The variable to store array
     *
     * @var array
     */
    protected $data = array();

    /**
     * Returns a *stringify* or user defined($default) parameter value
     *
     * @param  string $name    The parameter name
     * @param  mixed  $default The default parameter value if the parameter does not exist
     * @return string|null  The parameter value
     */
    public function __invoke($name, $default = '')
    {
        return isset($this->data[$name]) ? (string)$this->data[$name] : $default;
    }

    /**
     * Returns a *stringify* or user defined($default) parameter value
     *
     * @param  string $name    The parameter name
     * @param  mixed  $default The default parameter value if the parameter does not exist
     * @return string|null  The parameter value
     */
    public function get($name, $default = '')
    {
        return $this->__invoke($name, $default);
    }

    /**
     * Returns a integer value in the specified range
     *
     * @param string $name The parameter name
     * @param integer|null $min The min value for the parameter
     * @param integer|null $max The max value for the parameter
     * @return int The parameter value
     */
    public function getInt($name, $min = null, $max = null)
    {
        $value = (int) $this($name);

        if (!is_null($min) && $value < $min) {
            return $min;
        } elseif (!is_null($max) && $value > $max) {
            return $max;
        }

        return $value;
    }

    /**
     * Returns a array parameter value
     *
     * @param string $name The parameter name
     * @param array $default The default parameter value returned if the
     *                       parameter does not exist, should be array
     * @return array The parameter value
     */
    public function getArray($name, array $default = array())
    {
        return (array) $this->getRaw($name, $default);
    }

    /**
     * Returns a raw parameter value
     *
     * @param  string $name    The parameter name
     * @param  mixed  $default The default parameter value if the parameter does not exist
     * @return string The parameter value
     */
    public function getRaw($name, $default = null)
    {
        return isset($this->data[$name]) ? $this->data[$name] : $default;
    }

    /**
     * Returns a parameter value in the specified array, if not in, returns the
     * first element instead
     *
     * @param string $name The parameter name
     * @param array $array The array to be search
     * @return mixed The parameter value
     */
    public function getInArray($name, array $array)
    {
        $value = $this->getRaw($name);

        return in_array($value, $array) ? $value : $array[key($array)];
    }

    /**
     * Set parameter value
     *
     * @param string|array $name The parameter name or A key-value array
     * @param mixed $value The parameter value
     * @return Parameter
     */
    public function set($name, $value = null)
    {
        if (!is_array($name)) {
            $this->data[$name] = $value;
        } else {
            foreach ($name as $key => $value) {
                $this->data[$key] = $value;
            }
        }

        return $this;
    }

    /**
     * Remove parameter by specified name
     *
     * @param string $name The parameter name
     * @return Parameter
     */
    public function remove($name)
    {
        unset($this->data[$name]);

        return $this;
    }

    /**
     * Clear all parameter data
     *
     * @return Parameter
     */
    public function clear()
    {
        $this->data = array();

        return $this;
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
     * @return Parameter
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
