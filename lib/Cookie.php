<?php
/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2020 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

/**
 * A service that handles the HTTP request and response cookies
 *
 * @author      Twin Huang <twinhuang@qq.com>
 * @property    Request $request A service that handles the HTTP request data
 * @property    Response $response A service that handles the HTTP response data
 */
class Cookie extends Base implements \ArrayAccess, \IteratorAggregate
{
    /**
     * The cookie data
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
        parent::__construct($options);

        $this->data = &$this->request->getParameterReference('cookie');
    }

    /**
     * Get request cookie or set response cookie
     *
     * @param  string       $key     the name of cookie
     * @param  mixed        $value   the value of cookie
     * @param  array        $options options for set cookie
     * @return mixed
     */
    public function __invoke($key, $value = null, $options = array())
    {
        if (1 == func_num_args()) {
            return $this->get($key);
        } else {
            return $this->set($key, $value, $options);
        }
    }

    /**
     * Get request cookie
     *
     * @param  string $key
     * @param  mixed  $default default value
     * @return mixed
     */
    public function get($key, $default = null)
    {
        return isset($this->data[$key]) ? $this->data[$key] : $default;
    }

    /**
     * Set response cookie
     *
     * @param  string       $key     The name of cookie
     * @param  mixed        $value   The value of cookie
     * @param  array        $options
     * @return $this
     */
    public function set($key, $value = null, array $options = array())
    {
        if (isset($options['expires']) && 0 > $options['expires'] && isset($this->data[$key])) {
            unset($this->data[$key]);
        } else {
            $this->data[$key] = $value;
        }

        $this->response->setCookie($key, $value, $options);

        return $this;
    }

    /**
     * Remove response cookie
     *
     * @param string $key the name of cookie
     * @return $this
     */
    public function remove($key)
    {
        unset($this->data[$key]);
        $this->response->removeCookie($key);
        return $this;
    }

    /**
     * Clear all cookie data
     *
     * @return $this
     */
    public function clear()
    {
        $this->data = array();
        return $this;
    }

    /**
     * Returns all cookie data
     *
     * @return array
     */
    public function toArray()
    {
        return $this->data;
    }

    /**
     * Check if the offset exists
     *
     * @param  string $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->data);
    }

    /**
     * Get the offset value
     *
     * @param  string $offset
     * @return mixed
     */
    public function &offsetGet($offset)
    {
        return $this->data[$offset];
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
     * @return $this
     */
    public function offsetUnset($offset)
    {
        return $this->remove($offset);
    }

    /**
     * Retrieve an array iterator
     *
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->data);
    }
}
