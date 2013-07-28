<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Widget;

use Widget\Stdlib\Parameter;

/**
 * A widget that handles session parameters ($_SESSION)
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class Session extends Parameter
{
    /**
     * The namespace to store session data, disable on default
     *
     * @var string
     */
    protected $namespace = false;

    /**
     * The session configuration options
     *
     * @var array
     * @link http://php.net/manual/en/session.configuration.php
     */
    protected $inis = array(
        'cache_limiter'     => 'private_no_expire',
        'cookie_lifetime'   => 86400,
        'cache_expire'      => 86400,
        'gc_maxlifetime'    => 86400
    );

    /**
     * Constructor
     *
     * @param array $options
     */
    public function __construct(array $options = array())
    {
        parent::__construct($options + array(
            'inis' => $this->inis,
        ));

        $this->start();
    }

    /**
     * Start session
     *
     * @throws \RuntimeException When header has been sent
     * @return Session
     */
    protected function start()
    {
        $file = $line = null;
        if (headers_sent($file, $line)) {
            throw new \RuntimeException(sprintf('Unable to start session, output started at %s:%s', $file, $line));
        }

        // If session started, ignored it
        if (!session_id()) {
            session_start();
            if ($this->namespace) {
                if (!isset($_SESSION[$this->namespace])) {
                    $_SESSION[$this->namespace] = array();
                }
                $this->data = &$_SESSION[$this->namespace];
            } else {
                $this->data = &$_SESSION;
            }
        }

        return $this;
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
     * Destroy all session data
     *
     * @return Session
     */
    public function destroy()
    {
        if (session_id()) {
            session_destroy();
        }

        return $this->clear();
    }

    /**
     * Set session configuration options
     *
     * @param array $inis
     * @return Session
     */
    public function setInis($inis)
    {
        foreach ($inis as $name => $value) {
            ini_set('session.' . $name, $value);
        }

        return $this;
    }
}
