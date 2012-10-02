<?php

/**
 * Qwin Framework
 *
 * @copyright   Copyright (c) 2008-2012 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Qwin;

/**
 * Header
 *
 * @package     Qwin
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class Header extends ArrayWidget
{
    /**
     * Common use http status code and text
     * 
     * @var array
     * @todo other status codes
     */
    protected static $codeTexts = array(
        200 => 'OK',
        301 => 'Moved Permanently',
        302 => 'Found',
        304 => 'Not Modified',
        403 => 'Forbidden',
        404 => 'Not Found',
        500 => 'Internal Server Error',
    );
    
    /**
     * The http version, current is 1.0 or 1.1
     * 
     * @var string
     */
    protected $version = '1.1';
    
    /**
     * The status code
     * 
     * @var int
     */
    protected $statusCode = 200;
    
    /**
     * The status text for status code
     * 
     * @var string 
     */
    protected $statusText = 'OK';
    
    /**
     * Get or set the header values
     * 
     * @param string $name The header name
     * @param string|array $values The header values, for set method only
     * @param bool $replace Whether replace the exists values or not, for set method only
     * @return mixed|\Qwin\Header
     */
    public function __invoke($name, $values, $replace = true)
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
     * @param string $name The header name
     * @param string|array $values The header values
     * @param bool $replace Whether replace the exists values or not
     * @return \Qwin\Header
     */
    public function set($name, $values, $replace = true)
    {
        $values = (array)$values;
        
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
     * @param string $name The header name
     * @param mixed $default The default value
     * @param bool $first return the first element or the whole header values
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
     * Set the header status code
     * 
     * @param int $code The status code
     * @param string $text The status text
     * @return \Qwin\Header
     */
    public function setStatusCode($code, $text = null)
    {
        $this->statusCode = (int)$code;
        
        if ($text) {
            $this->statusText = $text;
        } elseif (isset(static::$codeTexts[$code])) {
            $this->statusText = static::$codeTexts[$code];
        }
        
        return $this;
    }
    
    /**
     * Get the status code
     * 
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }
    
    /**
     * Set the http version
     * 
     * @param string $version The http version
     * @return \Qwin\Header
     */
    public function setVersion($version)
    {
        $this->version = $version;
        return $this;
    }
    
    /**
     * Get the http version
     * 
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }
    
    /**
     * Send headers, including http status, raw headers and cookie
     * 
     * @return boolean|\Qwin\Header
     */
    public function send()
    {
        $file = $line = null;
        if (headers_sent($file, $line)) {
            $this->log(sprintf('Header has been at %s:%s', $file, $line));
            return false;
        }
        
        // send status
        header(sprintf('HTTP/%s %d %s', $this->version, $this->statusCode, $this->statusText));
        
        // send headers
        foreach ($this->data as $name => $values) {
            foreach ($values as $value) {
                header($name . ': ' . $value, false);
            }
        }
        
        // send cookie
        $this->cookie->send();
        
        return $this;
    }
}