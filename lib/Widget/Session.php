<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget;

/**
 * A widget that session parameters ($_SESSION)
 *
 * @author      Twin Huang <twinhuang@qq.com>
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
     * @return Session
     */
    protected function start()
    {
        $file = $line = null;
        if (headers_sent($file, $line)) {
            throw new Exception\RuntimeException(sprintf('Unable to start session, output started at %s:%s', $file, $line));
        }

        // If session started, ignored it
        if (!session_id()) {
            session_start();
            if (!isset($_SESSION[$this->namespace])) {
                $_SESSION[$this->namespace] = array();
            }
            $this->data = &$_SESSION[$this->namespace];
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
        if (session_id()) {
            session_destroy();
        }
        
        // Clean up all data
        $this->data = array();

        return $this;
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
