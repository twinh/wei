<?php
/**
 * Qwin Framework
 *
 * @copyright   Twin Huang Copyright (c) 2008-2012
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Qwin;

/**
 * Get
 *
 * @package     Qwin
 * @author      Twin Huang <twinh@yahoo.cn>
 * @todo        add init data support
 * @todo        add array param support
 */
class Get extends Request
{
    public function __construct($options = null)
    {
        parent::__construct($options);
        
        $params = $this->router->matchRequestUri();
        $this->data = $params ? $params : $_GET;
    }

    /**
     * Set get data
     *
     * @param string|array $name
     * @param mixed $value
     * @return Qwin_Get
     */
    public function set($name, $value = null, array $options = array())
    {
        if (is_array($name)) {
            foreach ($name as $key => $value) {
                $this->data[$key] = $value;
                $this->request->set($name, $value);
            }
        } else {
            $this->data[$name] = $value;
            $this->request->set($name, $value);
        }
        return $this;
    }
}