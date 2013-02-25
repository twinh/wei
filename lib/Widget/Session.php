<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget;

/**
 * Session
 *
 * @author      Twin Huang <twinh@yahoo.cn>
 * @todo        remove global variable $_SESSION
 */
class Session extends Parameter
{
    /**
     * Current namespace to store session data
     *
     * @var string
     */
    protected $namespace = 'widget';

    /**
     * Whether session started
     *
     * @var string
     */
    protected $started = false;

    /**
     * Whether auto start session when object construct
     *
     * @var bool
     */
    protected $autoStart = true;

    /**
     * @var array           Options
     *
     * @link http://php.net/manual/en/session.configuration.php
     */
    protected $options = array(
        'cache_limiter'     => 'private_no_expire, must-revalidate',
        'cookie_lifetime'   => 86400,
        'cache_expire'      => 86400,
    );

    public function __construct(array $options = array())
    {
        parent::__construct($options);

        if ($this->autoStart) {
            $this->start();
        }
    }

    /**
     * Get or set session
     *
     * @param  string       $key     the name of cookie
     * @param  mixed        $value   the value of cookie
     * @param  array        $options options for set cookie
     * @return mixed
     */
    public function __invoke($key, $value = null, array $options = array())
    {
        if (1 == func_num_args()) {
            return $this->get($key);
        } else {
            return $this->set($key, $value, $options);
        }
    }

    /**
     * Start session
     *
     * @return Session
     */
    public function start()
    {
        $file = $line = null;
        if (headers_sent($file, $line)) {
            throw new Exception(sprintf('Unable to start session, output started at %s:%s', $file, $line));
        }

        // If session started, ignored it
        if (!session_id()) {
            session_start();
            $this->started = true;
            if (!isset($_SESSION[$this->namespace])) {
                $_SESSION[$this->namespace] = array();
            }
            $this->data = &$_SESSION[$this->namespace];
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
        return $this->started;
    }

    /**
     * Set a session value in current namespace
     *
     * @param  string       $offset
     * @param  mixed        $value
     * @return mixed
     */
    public function set($offset, $value)
    {
        return $this->offsetSet($offset, $value);
    }

    /**
     * Get a session from current namespace
     *
     * @param  string $name
     * @param string $offset
     * @return mixed
     */
    public function get($offset)
    {
        return $this->offsetGet($offset);
    }

    /**
     * Clear session in $namespace or current namespace
     *
     * @param  string|null       $namespace
     * @return Session
     */
    public function clear($namespace = null)
    {
        if ($namespace) {
            $_SESSION[$namespace] = array();
        } else {
            $_SESSION[$this->namespace] = array();

            // clean up data for cli mode
            $this->data = array();
        }

        return $this;
    }

    /**
     * Destroy session
     *
     * @return Session
     */
    public function destroy()
    {
        if ($this->started) {
            session_destroy();
        }

        // clean up all data
        $this->data = array();

        return $this;
    }

    /**
     * Set session options
     *
     * @param array $options
     * @return Session
     */
    public function setOptions($options)
    {
        foreach ($options as $name => $value) {
            ini_set('session.' . $name, $value);
        }

        return $this;
    }
}
