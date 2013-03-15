<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget;

/**
 * The HTTP Request widget
 *
 * @author      Twin Huang <twinh@yahoo.cn>
 * @property    \Widget\Server $server The server widget
 */
class Request extends Parameter
{
    /**
     * The URL query parameters, equal to $_GET if $fromGlobal is true
     * 
     * @var array
     */
    protected $gets;
    
    /**
     * The HTTP request parameters, equal to $_POST if $fromGlobal is true
     * 
     * @var array
     */
    protected $posts;
    
    /**
     * The cookie parameters, equal to $_COOKIE if $fromGlobal is true
     * 
     * @var array 
     */
    protected $cookies;
    
    /**
     * The server parameters, equal to $_SERVER if $fromGlobal is true
     * 
     * @var array
     */
    protected $servers;
    
    /**
     * The upload file parameters, equal to $_FILES if $fromGlobal is true
     * 
     * @var array
     */
    protected $files;
    
    /**
     * Whether create request parameter from PHP global varibales
     * 
     * @var bool
     */
    protected $fromGlobal = true;
    
    /**
     * Constructor
     *
     * @param array $options
     */
    public function __construct(array $options = array())
    {
        parent::__construct($options);

        if ($this->fromGlobal) {
            $this->gets     = &$_GET;
            $this->posts    = &$_POST;
            $this->cookies  = &$_COOKIE;
            $this->servers  = &$_SERVER;
            $this->files    = &$_FILES;
            $this->data     = &$_REQUEST;
        }
    }
    
    /**
     * Returns the full url, which do not contain the fragment, for it never sent to the server
     *
     * @return string
     * @link http://snipplr.com/view.php?codeview&id=2734
     */
    public function getFullUrl()
    {
        $s = $this->server['HTTPS'] == 'on' ? 's' : '';
        $protocol = substr(strtolower($this->server['SERVER_PROTOCOL']), 0, strpos(strtolower($this->server['SERVER_PROTOCOL']), '/')) . $s;
        $port = ($this->server['SERVER_PORT'] == '80') ? '' : (':' . $this->server['SERVER_PORT']);

        return $protocol . '://' . $this->server['SERVER_NAME'] . $port . $this->server['REQUEST_URI'];
    }
    
    /**
     * Returns the client IP address
     *
     * @param  string $default The default ip address
     * @return string
     */
    public function getIp($default = '0.0.0.0')
    {
        return $this->server['HTTP_X_FORWARDED_FOR']
            ?: $this->server['HTTP_CLIENT_IP']
            ?: $this->server['REMOTE_ADDR']
            ?: $default;
    }
    
    /**
     * Check if the current request method is the specified string
     * 
     * @param string $method The method name to be compared
     * @return bool
     */
    public function inMethod($method)
    {
        return $method === strtoupper($this->server['REQUEST_METHOD']);
    }
    
    /**
     * Check if the current request method is GET 
     * 
     * @return bool
     */
    public function inGet()
    {
        return $this->inMethod('GET');
    }
    
    /**
     * Check if the current request method is POST 
     * 
     * @return bool
     */
    public function inPost()
    {
        return $this->inMethod('POST');
    }
    
    /**
     * Check if the current request is an ajax(XMLHttpRequest) request
     * 
     * @return bool
     */
    public function inAjax()
    {
        return 'xmlhttprequest' == strtolower($this->server['HTTP_X_REQUESTED_WITH']);
    }
    
    /**
     * Returns the HTTP parameters reference
     * 
     * @param string $type The parameter type, could be get, post, cookie, or server
     * @return array The parameters array
     */
    public function &getParameterReference($type)
    {
        if (in_array($type, array('get', 'post', 'cookie', 'server'))) {
            return $this->{$type . 's'};
        }
        
        throw new Exception\InvalidArgumentException(sprintf('Unkonwn parameter type "%s"', $type));
    }
}
