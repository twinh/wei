<?php
/**
 * Qwin Framework
 * 
 * @copyright   Copyright (c) 2008-2012 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Qwin;

/**
 * Cookie
 *
 * @package     Qwin
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class Cookie extends ArrayWidget
{
    /**
     * @var array Options
     * @see http://php.net/manual/en/function.setcookie.php
     */
    public $options = array(
        'parameters' => false,
        'expire' => 86400,
        'path' => '/',
        'domain' => null,
        'secure' => false,
        'httpOnly' => false,
        'raw' => false,
    );

    public function __construct(array $options = array())
    {
        parent::__construct($options);
        
        if (is_array($this->options['parameters'])) {
            $this->data = $this->options['parameters'];
        } else {
            $this->data = $_COOKIE;
        }
    }

    /**
     * Get or set cookie
     *
     * @param string $key the name of cookie
     * @param mixed $value the value of cookie
     * @param array $options options for set cookie
     * @return Qwin_Cookie
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
     * Get cookie
     *
     * @param string $key
     * @param mixed $default default value
     * @return mixed
     */
    public function get($key, $default = null)
    {
        return isset($this->data[$key]) ? @unserialize($this->data[$key]) : $default;
    }

    /**
     * Set cookie
     *
     * @param string $key the name of cookie
     * @param mixed $value the value of cookie
     * @param array $options
     */
    public function set($key, $value = null, array $options = array())
    {
        $value = serialize($value);
        $this->data[$key] = serialize($value);

        $o = $options + $this->options;

        $fn = $o['raw'] ? 'setrawcookie' : 'setcookie';
        $fn($key, $value, time() + $o['expire'], $o['path'], $o['domain'], $o['secure'], $o['httpOnly']);

        if (0 < $o['expire']) {
            $this->data[$key] = $value;
            $this->request->set($key, $value);
        } else {
            unset($this->data[$key]);
        }

        return $this;
    }

    /**
     * Remove cookie
     *
     * @param string $key the name of cookie
     */
    public function remove($key)
    {
        if (isset($this->data[$key])) {
            unset($this->data[$key]);
            setcookie($key, null, -1);
        }
        return $this;
    }

    /**
     * Remove cookie
     *
     * @param string $key the name of cookie
     * @return Qwin_Cookie
     */
    public function offsetUnset($key)
    {
        return $this->remove($key);
    }
}
