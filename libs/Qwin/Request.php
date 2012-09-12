<?php
/**
 * Qwin Framework
 *
 * @copyright   Copyright (c) 2008-2012 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Qwin;

/**
 * Request
 *
 * @package     Qwin
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class Request extends ArrayWidget
{
    public function __construct(array $options = array())
    {
        parent::__construct($options);
        
        $params = $this->router->matchRequestUri();
        $this->data = $params + $_REQUEST;
    }

    /**
     * Get request data as widget variable
     *
     * @param string $name
     * @param mixed $default
     * @return Qwin_Widget
     */
    public function __invoke($name, $default = null, array $options = array())
    {
        return isset($this->data[$name]) ? $this->data[$name] : $default;
    }

    /**
     * set request data
     *
     * @param string|array $name
     * @param mixed $value
     * @return Qwin_Reqeust
     */
    public function set($name, $value = null, array $options = array())
    {
        if (is_array($name)) {
            foreach ($name as $key => $value) {
                $this->data[$key] = $value;
            }
        } else {
            $this->data[$name] = $value;
        }
        return $this;
    }

    /**
     * Remove get data
     *
     * @param string $name
     * @return Qwin_Request
     */
    public function remove($name)
    {
        if (isset($this->data[$name])) {
            unset($this->data[$name]);
        }
        return $this;
    }
}
