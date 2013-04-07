<?php

/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget;

/**
 * The response header widget
 *
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class Header extends ArrayWidget
{
    /**
     * Constructor
     * 
     * @param array $options
     */
    public function __construct(array $options = array())
    {
        parent::__construct($options);
        
        $this->data = &$this->response->getHeaderReference();
    }
    
    /**
     * Get or set the header values
     *
     * @param  string|array $name    The header name or an associative array 
     *                               that the key is header name and the value 
     *                               is header value
     * @param  string|array $values  The header values, for set method only
     * @param  bool         $replace Whether replace the exists values or not, for set method only
     * @return mixed|\Widget\Header
     */
    public function __invoke($name, $values = null, $replace = true)
    {
        if (1 == func_num_args()) {
            return $this->get($name);
        } else {
            return $this->set($name, $values, $replace);
        }
    }

    /**
     * Set the header string
     *
     * @param  string       $name    The header name
     * @param  string|array $values  The header values
     * @param  bool         $replace Whether replace the exists values or not
     * @return \Widget\Header
     */
    public function set($name, $values = null, $replace = true)
    {
        if (is_array($name)) {
            foreach ($name as $key => $value) {
                $this->set($key, $value);
            }
            return $this;
        }
        
        $values = (array) $values;

        if (true === $replace || !isset($this->data[$name])) {
            $this->data[$name] = $values;
        } else {
            $this->data[$name] = array_merge($this->data[$name], $values);
        }

        return $this;
    }

    /**
     * Get the header string
     *
     * @param  string $name    The header name
     * @param  mixed  $default The default value
     * @param  bool   $first   return the first element or the whole header values
     * @return mixed
     */
    public function get($name, $default = null, $first = true)
    {
        if (!isset($this->data[$name])) {
            return $default;
        }

        if (is_array($this->data[$name]) && $first) {
            return current($this->data[$name]);
        }

        return $this->data[$name];
    }
    
    /**
     * Remove header by specified name
     * 
     * @param string $name The header name
     * @return \Widget\Header
     */
    public function remove($name)
    {
        unset($this->data[$name]);
        
        return $this;
    }
    
    /**
     * Returns response header as string
     * 
     * @return string
     */
    public function __toString()
    {
        $string = '';
        foreach ($this->data as $name => $values) {
            foreach ($values as $value) {
                $string .= $name . ': ' . $value . "\r\n";
            }
        }
        return $string;
    }
}
