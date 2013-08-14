<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Widget;

/**
 * A pure configuration widget for your application
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class Config extends Base
{
    /**
     * Returns the value of website configuration
     *
     * @param string $name The name of configuration(widget option)
     * @return mixed
     */
    public function __invoke($name)
    {
        return $this->get($name);
    }

    /**
     * Returns the value of configuration
     *
     * @param string $name The name of configuration(widget option)
     * @param mixed $default The default value
     * @return mixed
     */
    public function get($name, $default = null)
    {
        return isset($this->$name) ? $this->$name : $default;
    }

    /**
     * Set the value of website configuration
     *
     * @param string $name The name of configuration(widget option)
     * @param mixed $value The value of configuration
     * @return Config
     */
    public function set($name, $value)
    {
        $this->$name = $value;
        return $this;
    }
}
