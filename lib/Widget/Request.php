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
 * The methods are deviced from code of the Zend Framework (2.1-dev 2013-04-01)
 *   * getBaseUrl
 *   * getRequestUri
 *   * detectBaseUrl
 *   * detectRequestUri
 * 
 * @link      https://github.com/zendframework/zf2/blob/master/library/Zend/Http/PhpEnvironment/Request.php
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
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
     * @var string
     */
    protected $baseUrl;
    
    /**
     * @var string
     */
    protected $pathInfo;
    
    /**
     * @var string
     */
    protected $requestUri;
    
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
     * Returns the request scheme
     * 
     * @return string
     */
    public function getScheme()
    {
        if ('on' === strtolower($this->server['HTTPS']) || 1 == $this->server['HTTPS']) {
            return 'https';
        } else {
            return 'http';
        }
    }
    
    /**
     * Returns the request host
     * 
     * @return string
     */
    public function getHost()
    {
        return $this->server['HTTP_HOST'] 
            ?: $this->server['SERVER_NAME']
            ?: $this->server['REMOTE_ADDR'];
    }
    
    /**
     * Set the request URI.
     *
     * @param  string $requestUri
     * @return self
     */
    public function setRequestUri($requestUri)
    {
        $this->requestUri = $requestUri;
        return $this;
    }

    /**
     * Get the request URI.
     *
     * @return string
     */
    public function getRequestUri()
    {
        if ($this->requestUri === null) {
            $this->requestUri = $this->detectRequestUri();
        }
        return $this->requestUri;
    }

    /**
     * Set the base URL.
     *
     * @param  string $baseUrl
     * @return self
     */
    public function setBaseUrl($baseUrl)
    {
        $this->baseUrl = rtrim($baseUrl, '/');
        return $this;
    }

    /**
     * Get the base URL.
     *
     * @return string
     */
    public function getBaseUrl()
    {
        if ($this->baseUrl === null) {
            $this->setBaseUrl($this->detectBaseUrl());
        }
        return $this->baseUrl;
    }
    
    /**
     * Set the path info
     * 
     * @param string $pathInfo
     * @return \Widget\Request
     */
    public function setPathInfo($pathInfo)
    {
        $this->pathInfo = $pathInfo;
        return $this;
    }

    /**
     * Return request path info
     *
     * @return string
     */
    public function getPathInfo()
    {
        if ($this->pathInfo === null) {
            $this->pathInfo = $this->detectPathInfo();
        }
        return $this->pathInfo;
    }

    /**
     * Returns the full URL, which contains scheme://domain:port/path?queryString
     * 
     * The full URL do not contain the fragment, for it never sent to the server
     * 
     * @return string
     */
    public function getUrl()
    {
        $port = $this->server['SERVER_PORT'];
        if ($port == 80 || $port == 433) {
            $port = '';
        } else {
            $port = ':' . $port;
        }
        
        return $this->getScheme() . '://' . $this->getHost() . $port . $this->getRequestUri();
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
        return $this->server['REQUEST_METHOD'] . ' ' . $this->getUrl() . ' ' . $this->server['SERVER_PROTOCOL'] . "\r\n"
            . $header
            . $this->getContent();
    }
    
    /**
     * Detect the base URI for the request
     *
     * Looks at a variety of criteria in order to attempt to autodetect a base
     * URI, including rewrite URIs, proxy URIs, etc.
     *
     * @return string
     */
    protected function detectRequestUri()
    {
        $requestUri = null;
        $server     = $this->server;

        // Check this first so IIS will catch.
        $httpXRewriteUrl = $server->get('HTTP_X_REWRITE_URL');
        if ($httpXRewriteUrl !== null) {
            $requestUri = $httpXRewriteUrl;
        }

        // Check for IIS 7.0 or later with ISAPI_Rewrite
        $httpXOriginalUrl = $server->get('HTTP_X_ORIGINAL_URL');
        if ($httpXOriginalUrl !== null) {
            $requestUri = $httpXOriginalUrl;
        }

        // IIS7 with URL Rewrite: make sure we get the unencoded url
        // (double slash problem).
        $iisUrlRewritten = $server->get('IIS_WasUrlRewritten');
        $unencodedUrl    = $server->get('UNENCODED_URL', '');
        if ('1' == $iisUrlRewritten && '' !== $unencodedUrl) {
            return $unencodedUrl;
        }

        // HTTP proxy requests setup request URI with scheme and host [and port]
        // + the URL path, only use URL path.
        if (!$httpXRewriteUrl) {
            $requestUri = $server->get('REQUEST_URI');
        }

        if ($requestUri !== null) {
            return preg_replace('#^[^/:]+://[^/]+#', '', $requestUri);
        }

        // IIS 5.0, PHP as CGI.
        $origPathInfo = $server->get('ORIG_PATH_INFO');
        if ($origPathInfo !== null) {
            $queryString = $server->get('QUERY_STRING', '');
            if ($queryString !== '') {
                $origPathInfo .= '?' . $queryString;
            }
            return $origPathInfo;
        }

        return '/';
    }

    /**
     * Auto-detect the base path from the request environment
     *
     * Uses a variety of criteria in order to detect the base URL of the request
     * (i.e., anything additional to the document root).
     *
     * The base URL includes the schema, host, and port, in addition to the path.
     *
     * @return string
     */
    protected function detectBaseUrl()
    {
        $baseUrl        = '';
        $filename       = $this->server->get('SCRIPT_FILENAME', '');
        $scriptName     = $this->server->get('SCRIPT_NAME');
        $phpSelf        = $this->server->get('PHP_SELF');
        $origScriptName = $this->server->get('ORIG_SCRIPT_NAME');

        if ($scriptName !== null && basename($scriptName) === $filename) {
            $baseUrl = $scriptName;
        } elseif ($phpSelf !== null && basename($phpSelf) === $filename) {
            $baseUrl = $phpSelf;
        } elseif ($origScriptName !== null && basename($origScriptName) === $filename) {
            // 1and1 shared hosting compatibility.
            $baseUrl = $origScriptName;
        } else {
            // Backtrack up the SCRIPT_FILENAME to find the portion
            // matching PHP_SELF.

            $baseUrl  = '/';
            $basename = basename($filename);
            if ($basename) {
                $path     = ($phpSelf ? trim($phpSelf, '/') : '');
                $baseUrl .= substr($path, 0, strpos($path, $basename)) . $basename;
            }
        }

        // Does the base URL have anything in common with the request URI?
        $requestUri = $this->getRequestUri();

        // Full base URL matches.
        if (0 === strpos($requestUri, $baseUrl)) {
            return $baseUrl;
        }

        // Directory portion of base path matches.
        $baseDir = str_replace('\\', '/', dirname($baseUrl));
        if (0 === strpos($requestUri, $baseDir)) {
            return $baseDir;
        }

        $truncatedRequestUri = $requestUri;

        if (false !== ($pos = strpos($requestUri, '?'))) {
            $truncatedRequestUri = substr($requestUri, 0, $pos);
        }

        $basename = basename($baseUrl);

        // No match whatsoever
        if (empty($basename) || false === strpos($truncatedRequestUri, $basename)) {
            return '';
        }

        // If using mod_rewrite or ISAPI_Rewrite strip the script filename
        // out of the base path. $pos !== 0 makes sure it is not matching a
        // value from PATH_INFO or QUERY_STRING.
        if (strlen($requestUri) >= strlen($baseUrl)
            && (false !== ($pos = strpos($requestUri, $baseUrl)) && $pos !== 0)
        ) {
            $baseUrl = substr($requestUri, 0, $pos + strlen($baseUrl));
        }

        return $baseUrl;
    }
    
    /**
     * Detect the path info for the request
     * 
     * @return string
     */
    protected function detectPathInfo()
    {
        $uri = $this->getRequestUri();
        
        $pathInfo = '/' . trim(substr($uri, strlen($this->getBaseUrl())), '/');

        if (false !== $pos = strpos($pathInfo, '?')) {
            $pathInfo = substr($pathInfo, 0, $pos);
        }
        return $pathInfo;
    }
}
