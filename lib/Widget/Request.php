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
     * The request message body
     * 
     * @var string
     */
    protected $content;
    
    /**
     * Whether create request parameter from PHP global varibales
     * 
     * @var bool
     */
    protected $fromGlobal = true;
    
    /**
     * The base URI
     * 
     * @var string
     */
    protected $baseUri;
    
    /**
     * The HTTP request method
     * 
     * @var string
     */
    protected $method;
    
    /**
     * Constructor
     *
     * @param array $options
     */
    public function __construct(array $options = array())
    {
        parent::__construct($options);
        
        // Create paramters from super global variable on default
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
     * Returns the base URI
     * 
     * @return string
     */
    public function getBaseUri()
    {
        if (!$this->baseUri) {
            $uri = strtr(dirname($_SERVER['SCRIPT_NAME']), '\\', '/');
            $this->baseUri = '/' == $uri ? $uri : $uri . '/';
        }
        return $this->baseUri;
    }
    
    /**
     * Returns the domain in URL
     * 
     * @return string
     */
    public function getDomain()
    {
        return $this->server['SERVER_NAME'];
    }
    
    /**
     * Returns the base URL
     * 
     * The base URL contains scheme://domain:port/path
     * 
     * @link http://snipplr.com/view.php?codeview&id=2734
     * @return string
     */
    public function getBaseUrl()
    {
        $s = $this->server['HTTPS'] == 'on' ? 's' : '';
        $protocol = substr(strtolower($this->server['SERVER_PROTOCOL']), 0, strpos(strtolower($this->server['SERVER_PROTOCOL']), '/')) . $s;
        $port = ($this->server['SERVER_PORT'] == '80') ? '' : (':' . $this->server['SERVER_PORT']);
        
        return $protocol . '://' . $this->server['SERVER_NAME'] . $port;
    }
    
    /**
     * Returns the full url, which do not contain the fragment, for it never sent to the server
     *
     * The full URL contains scheme://domain:port/path?queryString
     * 
     * @return string
     */
    public function getFullUrl()
    {
        return $this->getBaseUrl() . $this->server['REQUEST_URI'];
    }
    
    /**
     * Return request path info
     * 
     * @return string
     */
    public function getPathInfo()
    {
        $uri = $this->server['REQUEST_URI'] // Apache2 & Nginx
            ?: $this->server['HTTP_X_ORIGINAL_URL'] // IIS7 + Rewrite Module
            ?: $this->server['HTTP_X_REWRITE_URL'] // IIS6 + ISAPI Rewite
            ?: '';

        $uri = '/' . substr($uri, strlen($this->getBaseUri()));
        
        if (false !== $pos = strpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }
        return $uri;
    }
    
    /**
     * Returns the client IP address
     * 
     * If the IP could not receive from the server parameter, or the IP address
     * is not valid, return the $default value instead
     * 
     * @link http://en.wikipedia.org/wiki/X-Forwarded-For
     * @param  string $default The default ip address
     * @return string
     */
    public function getIp($default = '0.0.0.0')
    {
        $ip = $this->server['HTTP_X_FORWARDED_FOR']
            ? current(explode(',', $this->server['HTTP_X_FORWARDED_FOR'])) : $this->server['HTTP_CLIENT_IP']
            ?: $this->server['REMOTE_ADDR'];

        return filter_var($ip, FILTER_VALIDATE_IP) ? $ip : $default;
    }
    
    /**
     * Returns the HTTP request method
     * 
     * @return string
     */
    public function getMethod()
    {
        if (null === $this->method) {
            $this->method = $this->server->get('REQUEST_METHOD', 'GET');
        }
        return $this->method;
    }
    
    /**
     * Set the HTTP request method
     * 
     * @param string $method The value of method
     * @return \Widget\Request
     */
    public function setMethod($method)
    {
        $this->method = $method;
        return $this;
    }
    
    /**
     * Check if the current request method is the specified string
     * 
     * @param string $method The method name to be compared
     * @return bool
     */
    public function inMethod($method)
    {
        return !strcasecmp($method, $this->getMethod());
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
    
    /**
     * Returns the request message body
     * 
     * @return string
     */
    public function getContent()
    {
        if (null === $this->content && $this->fromGlobal) {
            $this->content = file_get_contents('php://input');
        }
        return $this->content;
    }
    
    /**
     * Set the request message body
     * 
     * @param string $content
     * @return \Widget\Request
     */
    public function setContent($content)
    {
        $this->content = $content;
        
        return $this;
    }
    
    /**
     * Returns the request message string
     * 
     * @return string
     */
    public function __toString()
    {
        $header = '';
        foreach ($this->server->getHeaders() as $name => $value) {
            $name = implode('-', array_map('ucfirst', explode('_', strtolower($name))));
            $header .= $name . ': ' . $value . "\r\n";
        } 
        return $this->server['REQUEST_METHOD'] . ' ' . $this->getFullUrl() . ' ' . $this->server['SERVER_PROTOCOL'] . "\r\n"
            . $header
            . $this->getContent();
    }
}
