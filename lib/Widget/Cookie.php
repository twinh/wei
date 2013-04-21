<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget;

/**
 * The cookie widget
 *
 * @author      Twin Huang <twinhuang@qq.com>
 * @property    \Widget\Request $request The HTTP request widget
 */
class Cookie extends Parameter
{
    /**
     * @var array Options
     * @link http://php.net/manual/en/function.setcookie.php
     */
    protected $options = array(
        'expire'        => 86400,
        'path'          => '/',
        'domain'        => null,
        'secure'        => false,
        'httpOnly'      => false,
        'raw'           => false,
    );

    /**
     * The cookies that have not been sent to header, but will sent when class destruct
     *
     * @var array
     * @see \Widget\Cookie::__destruct
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
     * @return \Widget\Cookie
     */
    public function set($key, $value = null, array $options = array())
    {
        if (isset($options['expire']) && 0 > $options['expire'] && isset($this->data[$key])) {
             unset($this->data[$key]);
        } else {
            $this->data[$key] = $value;
        }

        $this->rawCookies[$key] = array(
            'value' => $value
        ) + $options + $this->options;

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
                'expire' => -1
            ));
        }

        return $this;
    }

    /**
     * Remove cookie
     *
     * @param  string       $key the name of cookie
     * @return \Widget\Cookie
     */
    public function offsetUnset($key)
    {
        return $this->remove($key);
    }

    /**
     * Send cookie to header
     *
     * @return \Widget\Cookie
     */
    public function send()
    {
        foreach ($this->rawCookies as $name => $o) {
            $fn = $o['raw'] ? 'setrawcookie' : 'setcookie';
            $fn($name, $o['value'], time() + $o['expire'], $o['path'], $o['domain'], $o['secure'], $o['httpOnly']);
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
}
