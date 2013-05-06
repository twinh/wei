<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Widget;

/**
 * A widget manager the HTTP cookie
 *
 * @author      Twin Huang <twinhuang@qq.com>
 * @property    Request $request The HTTP request widget
 * 
 */
class Cookie extends Parameter
{
    /**
     * The lifetime of cookie (seconds)
     * 
     * @var int
     * @link http://php.net/manual/en/function.setcookie.php
     */
    protected $expires = 86400;
    
    /**
     * The path on the server in which the cookie will be available on
     *
     * @var string 
     */
    protected $path = '/';
    
    /**
     * The domain that the cookie is available to
     *
     * @var string
     */
    protected $domain = null;
    
    /**
     * Indicates that the cookie should only be transmitted over a secure HTTPS connection from the client
     * 
     * @var bool
     */
    protected $secure = false;
    
    /**
     * When TRUE the cookie will be made accessible only through the HTTP protocol
     * 
     * @var bool
     */
    protected $httpOnly = false;
    
    /**
     * Whether send a cookie without urlencoding the cookie value
     * 
     * @var bool
     */
    protected $raw = false;

    /**
     * The cookies that have not been sent to header, but will sent when class destruct
     *
     * @var array
     * @see Cookie::__destruct
     */
    protected $rawCookies = array();

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
     * Get or set cookie
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
     * Get cookie
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
     * Set cookie
     *
     * @param  string       $key     The name of cookie
     * @param  mixed        $value   The value of cookie
     * @param  array        $options
     * @return Cookie
     */
    public function set($key, $value = null, array $options = array())
    {
        if (isset($options['expires']) && 0 > $options['expires'] && isset($this->data[$key])) {
             unset($this->data[$key]);
        } else {
            $this->data[$key] = $value;
        }

        $this->rawCookies[$key] = array('value' => $value) + $options;

        return $this;
    }

    /**
     * Remove cookie
     *
     * @param string $key the name of cookie
     * @return Cookie
     */
    public function remove($key)
    {
        if (isset($this->data[$key])) {
            $this->set($key, null, array(
                'expires' => -1
            ));
        }

        return $this;
    }

    /**
     * Remove cookie
     *
     * @param  string       $key the name of cookie
     * @return Cookie
     */
    public function offsetUnset($key)
    {
        return $this->remove($key);
    }

    /**
     * Send cookie to header
     *
     * @return Cookie
     */
    public function send()
    {
        $time = time();
        foreach ($this->rawCookies as $name => $options) {
            $fn = $this->resolveValue($options, 'raw') ? 'setrawcookie' : 'setcookie';
            $fn(
                $name, 
                $options['value'], 
                $time + $this->resolveValue($options, 'expires'), 
                $this->resolveValue($options, 'path'),
                $this->resolveValue($options, 'domain'),
                $this->resolveValue($options, 'secure'),
                $this->resolveValue($options, 'httpOnly')
            );
            unset($this->rawCookies[$name]);
        }

        return $this;
    }
    
    /**
     * Destructor
     * 
     * @todo send or not ?
     */
    public function __destruct()
    {
        if (!headers_sent()) {
            $this->send();
        }
    }
    
    /**
     * Resolve the options value
     * 
     * @param array $options
     * @param string $key The name of property
     * @return mixed
     */
    protected function resolveValue($options, $key)
    {
        return isset($options[$key]) ? $options[$key] : $this->$key;
    }
}
