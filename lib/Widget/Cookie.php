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
 * A widget manager the HTTP request and response cookie
 *
 * @author      Twin Huang <twinhuang@qq.com>
 * @property    Request $request A widget that handles the HTTP request data
 * @property    Response $response A widget that handles the HTTP response data
 */
class Cookie extends Parameter
{
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
     * @return Cookie
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
     * @return Cookie
     */
    public function remove($key)
    {
        unset($this->data[$key]);
        $this->response->removeCookie($key);

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
}
