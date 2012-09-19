<?php
/**
 * Qwin Framework
 *
 * @copyright   Copyright (c) 2008-2012 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Qwin;

/**
 * ArrayWidget
 *
 * @package     Qwin
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class ArrayWidget extends Widget implements \ArrayAccess
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
     * @param string $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->data[$offset]);
    }

    /**
     * Get the offset value
     *
     * @param string $offset
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
     * @param mixed $value
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
     * @param array $array
     * @return Qwin_ArrayWidget
     */
    public function fromArray($array)
    {
        $this->data = (array)$array + $this->data;
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
}