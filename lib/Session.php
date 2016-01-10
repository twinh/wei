<?php
/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2015 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

/**
 * A service that handles session data ($_SESSION)
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class Session extends Base implements \ArrayAccess, \Countable, \IteratorAggregate
{
    /**
     * The namespace to store session data
     *
     * @var string|false
     */
    protected $namespace = false;

    /**
     * The session data
     *
     * @var array
     */
    protected $data = array();

    /**
     * The session configuration options
     *
     * @var array
     * @link http://php.net/manual/en/session.configuration.php
     */
    protected $inis = array();

    /**
     * Constructor
     *
     * @param array $options
     */
    public function __construct(array $options = array())
    {
        parent::__construct($options);
        $this->start();
    }

    /**
     * Start session
     *
     * @throws \RuntimeException When header has been sent
     */
    public function start()
    {
        if (!session_id()) {
            $file = $line = null;
            if (headers_sent($file, $line)) {
                throw new \RuntimeException(sprintf('Unable to start session, output started at %s:%s', $file, $line));
            }
            session_start();
        }

        if ($this->namespace) {
            if (!isset($_SESSION[$this->namespace])) {
                $_SESSION[$this->namespace] = array();
            }
            $this->data = &$_SESSION[$this->namespace];
        } else {
            $this->data = &$_SESSION;
        }
    }

    /**
     * Get or set session
     *
     * @param  string $key The name of cookie
     * @param  mixed $value The value of cookie
     * @return mixed
     */
    public function __invoke($key, $value = null)
    {
        if (1 == func_num_args()) {
            return $this->get($key);
        } else {
            return $this->set($key, $value);
        }
    }

    /**
     * Returns session value
     *
     * @param  string $key    The name of session
     * @param  mixed  $default The default parameter value if the session does not exist
     * @return mixed
     */
    public function get($key, $default = null)
    {
        return isset($this->data[$key]) ? $this->data[$key] : $default;
    }

    /**
     * Set session data
     *
     * @param string|array $name The session name or A key-value array
     * @param mixed $value The session value
     * @return $this
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
     * Remove session data by specified name
     *
     * @param string $name The name of session
     * @return $this
     */
    public function remove($name)
    {
        unset($this->data[$name]);
        return $this;
    }

    /**
     * Check if the session is exists
     *
     * @param string $name
     * @return bool
     */
    public function exists($name)
    {
        return array_key_exists($name, $this->data);
    }

    /**
     * Clear session data
     *
     * @return $this
     */
    public function clear()
    {
        $this->data = array();
        return $this;
    }

    /**
     * Destroy all session data
     *
     * @return $this
     */
    public function destroy()
    {
        if (session_id()) {
            session_destroy();
        }
        return $this->clear();
    }

    /**
     * Returns session data as array
     *
     * @return array
     */
    public function toArray()
    {
        return $this->data;
    }

    /**
     * Set session configuration options
     *
     * @param array $inis
     * @return $this
     */
    public function setInis($inis)
    {
        foreach ($inis as $name => $value) {
            ini_set('session.' . $name, $value);
        }
        $this->inis = $inis + $this->inis;
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
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        $this->data[$offset] = $value;
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
     * Return the length of data
     *
     * @return int the length of data
     */
    public function count()
    {
        return count($this->data);
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
