<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2012 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget;

/**
 * Session
 *
 * @package     Widget
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class Session extends ArrayWidget
{
    /**
     * Current namespace to store session data
     *
     * @var string
     */
    protected $_namespace;

    /**
     * Whether session started
     *
     * @var string
     */
    protected $_started = false;

    /**
     * @var array           Options
     *
     *      -- namespace    namespace to store session data
     *
     *      -- autoStart    whether auto start session when object construct
     *
     * @see http://php.net/manual/en/session.configuration.php
     */
    public $options = array(
        'namespace'         => 'widget',
        'autoStart'         => true,
        'cache_limiter'     => 'private_no_expire, must-revalidate',
        'cookie_lifetime'   => 86400,
        'cache_expire'      => 86400,
    );

    public function __construct($options = null)
    {
        // merge options
        $options = (array) $options + $this->options;
        $this->options = &$options;

        // init all options
        $this->option($options);

        if ($options['autoStart']) {
            $this->start();
        }
    }

    /**
     * Get or set options
     *
     * @param  mixed $name
     * @param  mixed $value
     * @return mixed
     */
    public function option($name = null, $value = null)
    {
        if (2 == func_num_args() && (is_string($name) || is_int($name))) {
            if ('name' == $name || false !== strpos($name, '_')) {
                ini_set('session.' . $name, $value);
            }
            // set option
            return parent::option($name, $value);
        }

        return parent::option($name);
    }
    
    /**
     * Return session value
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
     * Set current namespace
     *
     * @param  string       $name
     * @return Widget_Session
     */
    public function setNamespaceOption($name)
    {
        $this->options['namespace'] = $name;
        $this->_namespace = $name;

        return $this;
    }

    /**
     * Start session
     *
     * @return Widget_Session
     */
    public function start()
    {
        $file = $line = null;
        if (headers_sent($file, $line)) {
            throw new Exception(sprintf('Unable to start session, output started at %s:%s', $file, $line));
        }

        // session started, ignored
        if (!session_id()) {
            session_start();
            $this->_started = true;
            if (!isset($_SESSION[$this->_namespace])) {
                $_SESSION[$this->_namespace] = array();
            }
            $this->data = &$_SESSION[$this->_namespace];
        }

        return $this;
    }

    /**
     * Whether session started
     *
     * @return bool
     */
    public function isStarted()
    {
        return $this->_started;
    }

    /**
     * Set a session value in current namespace
     *
     * @param  string       $offset
     * @param  mixed        $value
     * @return Widget_Session
     */
    public function set($offset, $value)
    {
        return $this->offsetSet($offset, $value);
    }

    /**
     * Get a session from current namespace
     *
     * @param  string $name
     * @return mixed
     */
    public function get($offset)
    {
        return $this->offsetGet($offset);
    }

    /**
     * Clear session in $namespace or current namespace
     *
     * @param  string       $namespace
     * @return Widget_Session
     */
    public function clear($namespace = null)
    {
        if ($namespace) {
            $_SESSION[$namespace] = array();
        } else {
            $_SESSION[$this->_namespace] = array();

            // clean up data for cli mode
            $this->data = array();
        }

        return $this;
    }

    /**
     * Destroy session
     *
     * @return Widget_Session
     */
    public function destroy()
    {
        if ($this->_started) {
            session_destroy();
        }

        // clean up all data
        $this->data = array();

        return $this;
    }
}
