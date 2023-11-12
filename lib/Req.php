<?php

/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2022 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

use ReturnTypeWillChange;

/**
 * A service that handles the HTTP request data
 *
 * The methods are derived from code of the Zend Framework (2.1-dev 2013-04-01)
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
 * @author      Twin Huang <twinhuang@qq.com>
 */
class Req extends Base implements \ArrayAccess, \Countable, \IteratorAggregate
{
    public const HTTP_PORT = 80;

    public const HTTPS_PORT = 443;

    /**
     * The request parameters, equals to $_REQUEST when $fromGlobal is true
     *
     * @var array
     */
    protected $data = [];

    /**
     * The URL query parameters, equal to $_GET when $fromGlobal is true
     *
     * @var array
     */
    protected $gets = [];

    /**
     * The HTTP request parameters, equal to $_POST when $fromGlobal is true
     *
     * @var array
     */
    protected $posts = [];

    /**
     * The cookie parameters, equal to $_COOKIE when $fromGlobal is true
     *
     * @var array
     */
    protected $cookies = [];

    /**
     * The server parameters, equal to $_SERVER when $fromGlobal is true
     *
     * @var array
     */
    protected $servers = [];

    /**
     * The upload file parameters, equal to $_FILES when $fromGlobal is true
     *
     * @var array
     */
    protected $files = [];

    /**
     * The request message body
     *
     * @var string
     */
    protected $content;

    /**
     * Whether create request parameter from PHP global variable
     *
     * @var bool
     */
    protected $fromGlobal = true;

    /**
     * Whether overwrite the request method when "_method" request parameter is present
     *
     * @var bool
     */
    protected $overwriteMethod = true;

    /**
     * Whether add "X-Requested-With: XMLHttpRequest" header when "_ajax" request parameter is present
     *
     * @var
     */
    protected $overwriteAjax = true;

    /**
     * Whether allow detect accept MIME type by "_format" request parameter
     *
     * @var bool
     */
    protected $overwriteFormat = true;

    /**
     * The param name to receive path info for router, when URL rewrite is not enabled
     *
     * @var string
     */
    protected $routerKey = 'r';

    /**
     * A flag to indicate whether URL rewriting is enabled, since we cannot detected it in index page(path info is
     * always empty).
     *
     * @var bool
     */
    protected $defaultUrlRewrite = true;

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
     * Whether trust HTTP_X_FORWARDED_FOR HEADER
     *
     * NOTE: currently only support bool value
     *
     * @var array|bool
     */
    protected $trustedProxies = [];

    /**
     * The extra keys course by &offsetGet
     *
     * @var array
     */
    protected $extraKeys = [];

    /**
     * Constructor
     *
     * @param array $options
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    public function __construct(array $options = [])
    {
        parent::__construct($options);

        // Create parameters from super global variables on default
        if ($this->fromGlobal) {
            // phpcs:disable MySource.PHP.GetRequestData.SuperglobalAccessed
            $this->gets = &$_GET;
            $this->posts = &$_POST;
            $this->cookies = &$_COOKIE;
            $this->servers = &$_SERVER;
            $this->files = &$_FILES;
            $this->data = &$_REQUEST;
        }

        if (false !== strpos($this->getServer('HTTP_CONTENT_TYPE', ''), 'application/json')) {
            $this->data += (array) json_decode($this->getContent(), true);
        }
    }

    /**
     * Returns a *stringify* or user defined($default) parameter value
     *
     * @param string $name The parameter name
     * @param mixed $default The default parameter value if the parameter does not exist
     * @return string|null  The parameter value
     */
    public function __invoke($name, $default = '')
    {
        return isset($this->data[$name]) ? (string) $this->data[$name] : $default;
    }

    /**
     * Returns the request message string
     *
     * @return string
     */
    public function __toString()
    {
        $header = '';
        foreach ($this->getHeaders() as $name => $value) {
            $name = implode('-', array_map('ucfirst', explode('_', strtolower($name))));
            $header .= $name . ': ' . $value . "\r\n";
        }
        return $this->getServer('REQUEST_METHOD') . ' ' . $this->getUrl() . ' ' . $this->getServer('SERVER_PROTOCOL')
            . "\r\n"
            . $header
            . $this->getContent();
    }

    /**
     * Returns a *stringify* or user defined($default) parameter value
     *
     * @param string $name The parameter name
     * @param mixed $default The default parameter value if the parameter does not exist
     * @return string|null  The parameter value
     */
    public function get($name, $default = null)
    {
        return $this->__invoke($name, $default);
    }

    /**
     * Returns a integer value in the specified range
     *
     * @param string $name The parameter name
     * @param int|null $min The min value for the parameter
     * @param int|null $max The max value for the parameter
     * @return int The parameter value
     */
    public function getInt($name, $min = null, $max = null)
    {
        $value = (int) $this($name);

        if (null !== $min && $value < $min) {
            return $min;
        } elseif (null !== $max && $value > $max) {
            return $max;
        }

        return $value;
    }

    /**
     * Returns a parameter value in the specified array, if not in, returns the
     * first element instead
     *
     * @param string $name The parameter name
     * @param array $array The array to be search
     * @return mixed The parameter value
     */
    public function getInArray($name, array $array)
    {
        $value = $this->get($name);
        return in_array($value, $array, true) ? $value : $array[key($array)];
    }

    /**
     * Set parameter value
     *
     * @param string|array $name The parameter name or A key-value array
     * @param mixed $value The parameter value
     * @return $this
     */
    public function set($name, $value = null)
    {
        if (!is_array($name)) {
            $this->data[$name] = $value;
        } else {
            foreach ($name as $key => $value) {
                $this->data[$key] = $value;
            }
        }
        return $this;
    }

    /**
     * Remove parameter by specified name
     *
     * @param string $name The parameter name
     * @return $this
     */
    public function remove($name)
    {
        unset($this->data[$name]);
        return $this;
    }

    /**
     * Clear all parameter data
     *
     * @return $this
     */
    public function clear()
    {
        $this->data = [];
        $this->extraKeys = [];
        return $this;
    }

    /**
     * Check if the parameter has value
     *
     * For example, 0, ' ', 0.0 are all have value
     *
     * @param string $name
     * @return bool
     */
    public function has($name)
    {
        return isset($this->data[$name]) && !in_array($this->data[$name], ['', null, false, []], true);
    }

    /**
     * Check if the offset exists
     *
     * Use isset to make sure ArrayAccess acts more like array
     * To detect whether key exists, use `array_key_exists($key, $request->toArray())`
     *
     * @param string $offset
     * @return bool
     * @see \WeiTest\ReqTest::assertArrayBehaviour
     */
    public function offsetExists($offset): bool
    {
        return isset($this->data[$offset]);
    }

    /**
     * Get the offset value
     *
     * @param string $offset
     * @return mixed
     */
    #[ReturnTypeWillChange]
    public function &offsetGet($offset)
    {
        if (!isset($this->data[$offset])) {
            $this->extraKeys[$offset] = true;
        }
        return $this->data[$offset];
    }

    /**
     * Set the offset value
     *
     * @param string $offset
     * @param mixed $value
     * @return void
     */
    public function offsetSet($offset, $value): void
    {
        unset($this->extraKeys[$offset]);
        $this->data[$offset] = $value;
    }

    /**
     * Unset the offset
     *
     * @param string $offset
     */
    public function offsetUnset($offset): void
    {
        unset($this->extraKeys[$offset], $this->data[$offset]);
    }

    /**
     * Merge data from array
     *
     * @param array $array
     * @return $this
     */
    public function fromArray(array $array = [])
    {
        $this->data = $array;
        return $this;
    }

    /**
     * Returns the request parameters
     *
     * @return array
     */
    public function toArray()
    {
        return $this->getData();
    }

    /**
     * Returns the request parameters
     *
     * @return array
     */
    public function getData()
    {
        $this->removeExtraKeys();
        return $this->data;
    }

    /**
     * Return the length of data
     *
     * @return int the length of data
     */
    public function count(): int
    {
        return count($this->getData());
    }

    /**
     * Returns the request scheme
     *
     * @return string
     */
    public function getScheme()
    {
        $https = $this->getServer('HTTPS');
        if ($https && ('on' === strtolower($https) || 1 == $https)) {
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
        $host = $this->getServer('HTTP_HOST') ?: $this->getServer('SERVER_NAME') ?: $this->getServer('REMOTE_ADDR');
        return preg_replace('/:\d+$/', '', $host ?? '');
    }

    /**
     * Set the request URI.
     *
     * @param string $requestUri
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
        if (null === $this->requestUri) {
            $this->requestUri = $this->detectRequestUri();
        }
        return $this->requestUri;
    }

    /**
     * Set the base URL.
     *
     * @param string $baseUrl
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
        if (null === $this->baseUrl) {
            $this->setBaseUrl($this->detectBaseUrl());
        }
        return $this->baseUrl;
    }

    /**
     * Set the path info
     *
     * @param string $pathInfo
     * @return $this
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
        if (null === $this->pathInfo) {
            $this->pathInfo = $this->detectPathInfo();
        }
        return $this->pathInfo;
    }

    /**
     * Returns the full URL, which contains scheme://domain[:port]/[baseUrl][PathInfo][?queryString]
     *
     * The full URL do not contain the fragment, for it never sent to the server
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->getSchemeAndHost() . $this->getRequestUri();
    }

    /**
     * Returns scheme and host which contains scheme://domain[:port]
     *
     * @return string
     */
    public function getSchemeAndHost()
    {
        $port = $this->getServer('SERVER_PORT');
        if (static::HTTP_PORT == $port || static::HTTPS_PORT == $port || empty($port)) {
            $port = '';
        } else {
            $port = ':' . $port;
        }
        return $this->getScheme() . '://' . $this->getHost() . $port;
    }

    /**
     * Generates absolute URL for specified path
     *
     * @param string $path
     * @return string
     */
    public function getUrlFor($path)
    {
        return $this->getSchemeAndHost() . $path;
    }

    /**
     * Returns the client IP address
     *
     * If the IP could not receive from the server parameter, or the IP address
     * is not valid, return the $default value instead
     *
     * @link http://en.wikipedia.org/wiki/X-Forwarded-For
     * @param string $default The default ip address
     * @return string
     */
    public function getIp($default = '0.0.0.0')
    {
        if (!$this->trustedProxies) {
            $ip = $this->getServer('REMOTE_ADDR');
        } else {
            $ip = ($this->getServer('HTTP_X_FORWARDED_FOR') ?
                current(explode(',', $this->getServer('HTTP_X_FORWARDED_FOR')))
                : $this->getServer('HTTP_CLIENT_IP')
            ) ?: $this->getServer('REMOTE_ADDR');
        }

        return filter_var($ip, \FILTER_VALIDATE_IP) ? $ip : $default;
    }

    /**
     * Returns the HTTP request method
     *
     * @return string
     */
    public function getMethod()
    {
        if (null === $this->method) {
            if ($method = $this->getServer('HTTP_X_HTTP_METHOD_OVERRIDE')) {
                $this->method = strtoupper($method);
            } elseif (
                $this->overwriteMethod
                && 'POST' === $this->getServer('REQUEST_METHOD')
                && $method = $this->get('_method')
            ) {
                $this->method = strtoupper($method);
            } else {
                $this->method = $this->getServer('REQUEST_METHOD', 'GET');
            }
        }
        return $this->method;
    }

    /**
     * Set the HTTP request method
     *
     * @param string $method The value of method
     * @return $this
     */
    public function setMethod($method)
    {
        $this->method = strtoupper($method);
        return $this;
    }

    /**
     * Check if the current request method is the specified string
     *
     * @param string $method The method name to be compared
     * @return bool
     */
    public function isMethod($method)
    {
        return !strcasecmp($method, $this->getMethod());
    }

    /**
     * Check if the current request method is GET
     *
     * @return bool
     */
    public function isGet()
    {
        return $this->isMethod('GET');
    }

    /**
     * Check if the current request method is POST
     *
     * @return bool
     */
    public function isPost()
    {
        return $this->isMethod('POST');
    }

    /**
     * Check if the current request is an ajax(XMLHttpRequest) request
     *
     * @return bool
     */
    public function isAjax()
    {
        if ($this->overwriteAjax && $this->get('_ajax')) {
            return true;
        }
        return 'xmlhttprequest' == strtolower($this->getServer('HTTP_X_REQUESTED_WITH', ''));
    }

    /**
     * Returns the HTTP parameters reference
     *
     * @param string $type The parameter type, could be get, post, cookie, or server
     * @return array The parameters array
     * @throws \InvalidArgumentException When parameter type is unknown
     */
    public function &getParameterReference($type)
    {
        if (in_array($type, ['get', 'post', 'cookie', 'server', 'file'], true)) {
            return $this->{$type . 's'};
        }

        throw new \InvalidArgumentException(sprintf('Unknown parameter type "%s"', $type));
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
     * @return $this
     */
    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

    /**
     * Return the server and execution environment parameter value ($_SERVER)
     *
     * @param string $name The name of parameter
     * @param mixed $default The default parameter value if the parameter does not exist
     * @return mixed
     */
    public function getServer($name, $default = null)
    {
        return isset($this->servers[$name]) ? $this->servers[$name] : $default;
    }

    /**
     * Set the server and execution environment parameter value ($_SERVER)
     *
     * @param string $name
     * @param string $value
     * @return $this
     */
    public function setServer($name, $value = null)
    {
        if (!is_array($name)) {
            $this->servers[$name] = $value;
        } else {
            $this->servers = $name + $this->servers;
        }
        return $this;
    }

    /**
     * Return the URL query parameter value ($_GET)
     *
     * @param string $name The name of parameter
     * @param mixed $default The default parameter value if the parameter does not exist
     * @return mixed
     */
    public function getQuery($name, $default = null)
    {
        return isset($this->gets[$name]) ? $this->gets[$name] : $default;
    }

    /**
     * Set the URL query parameter value ($_GET)
     *
     * @param string $name
     * @param string $value
     * @return $this
     */
    public function setQuery($name, $value = null)
    {
        if (!is_array($name)) {
            $this->gets[$name] = $value;
        } else {
            $this->gets = $name + $this->gets;
        }
        return $this;
    }

    /**
     * Return the URL query parameter values ($_GET)
     *
     * @return array
     */
    public function &getQueries()
    {
        return $this->gets;
    }

    /**
     * Return the HTTP request parameters value ($_POST)
     *
     * @param string $name The name of parameter
     * @param mixed $default The default parameter value if the parameter does not exist
     * @return mixed
     */
    public function getPost($name, $default = null)
    {
        return isset($this->posts[$name]) ? $this->posts[$name] : $default;
    }

    /**
     * Set the HTTP request parameters value ($_POST)
     *
     * @param string $name
     * @param string $value
     */
    public function setPost($name, $value = null)
    {
        if (!is_array($name)) {
            $this->posts[$name] = $value;
        } else {
            $this->posts = $name + $this->posts;
        }
    }

    /**
     * Returns the HTTP request headers
     *
     * @return array
     */
    public function getHeaders()
    {
        $headers = [];
        foreach ($this->servers as $name => $value) {
            if (0 === strpos($name, 'HTTP_')) {
                $headers[substr($name, 5)] = $value;
            }
        }
        return $headers;
    }

    /**
     * Check if the request page is specified page
     *
     * @param string $page
     * @return bool
     */
    public function isPage($page)
    {
        return ltrim($this->getPathInfo()) === $page;
    }

    /**
     * Retrieve an array iterator
     *
     * @return \ArrayIterator
     */
    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->getData());
    }

    /**
     * Check if the accept header is *starts* with the specified MIME type
     *
     * @param string $mine
     * @return bool
     */
    public function accept($mine)
    {
        return 0 === strpos($this->getServer('HTTP_ACCEPT', ''), $mine);
    }

    /**
     * Check if the request is accept a JSON response
     *
     * @return bool
     */
    public function acceptJson()
    {
        if ($this->overwriteFormat && 'json' == $this->get('_format')) {
            return true;
        }
        return $this->accept('application/json');
    }

    /**
     * Check if the request accepts the specified format
     *
     * @param string $format
     * @return bool
     */
    public function isFormat($format)
    {
        if ('json' === $format) {
            return $this->acceptJson();
        }

        return $this->get('_format') === $format;
    }

    /**
     * Shorthand method to return referer url
     *
     * @return string
     */
    public function getReferer()
    {
        return $this->getServer('HTTP_REFERER');
    }

    /**
     * @return string
     */
    public function getRouterKey()
    {
        return $this->routerKey;
    }

    /**
     * Whether URL rewriting is enabled
     *
     * @return bool
     */
    public function isUrlRewrite()
    {
        if ('/' !== $this->getPathInfo()) {
            return true;
        }
        return $this->defaultUrlRewrite;
    }

    /**
     * Receive path info for router
     *
     * @return string
     */
    public function getRouterPathInfo()
    {
        if ($this->isUrlRewrite()) {
            return $this->getPathInfo();
        } else {
            return '/' . ltrim($this->get($this->routerKey, ''), '/');
        }
    }

    /**
     * Check if the specified header is set
     *
     * @param string $name
     * @return bool
     * @svc
     */
    protected function hasHeader(string $name): bool
    {
        return isset($this->servers['HTTP_' . strtoupper($name)]);
    }

    /**
     * Return the specified header value
     *
     * @param string $name
     * @return string|null
     * @svc
     */
    protected function getHeader(string $name): ?string
    {
        $name = 'HTTP_' . strtoupper($name);
        return $this->servers[$name] ?? null;
    }

    /**
     * Returns the server ip address
     *
     * @return string|null
     * @svc
     */
    protected function getServerIp(): ?string
    {
        return $this->getServer('SERVER_ADDR');
    }

    /**
     * Check if current request is a preflight request
     *
     * @return bool
     * @link https://developer.mozilla.org/en-US/docs/Glossary/Preflight_request
     * @svc
     */
    protected function isPreflight(): bool
    {
        return $this->isMethod('OPTIONS')
            && $this->hasHeader('ORIGIN')
            && $this->hasHeader('ACCESS_CONTROL_REQUEST_METHOD');
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

        // Check this first so IIS will catch.
        $httpXRewriteUrl = $this->getServer('HTTP_X_REWRITE_URL');
        if (null !== $httpXRewriteUrl) {
            $requestUri = $httpXRewriteUrl;
        }

        // Check for IIS 7.0 or later with ISAPI_Rewrite
        $httpXOriginalUrl = $this->getServer('HTTP_X_ORIGINAL_URL');
        if (null !== $httpXOriginalUrl) {
            $requestUri = $httpXOriginalUrl;
        }

        // IIS7 with URL Rewrite: make sure we get the unencoded url
        // (double slash problem).
        $iisUrlRewritten = $this->getServer('IIS_WasUrlRewritten');
        $unencodedUrl = $this->getServer('UNENCODED_URL', '');
        if ('1' == $iisUrlRewritten && '' !== $unencodedUrl) {
            return $unencodedUrl;
        }

        // HTTP proxy requests setup request URI with scheme and host [and port]
        // + the URL path, only use URL path.
        if (!$httpXRewriteUrl) {
            $requestUri = $this->getServer('REQUEST_URI');
        }

        if (null !== $requestUri) {
            return preg_replace('#^[^/:]+://[^/]+#', '', $requestUri);
        }

        // IIS 5.0, PHP as CGI.
        $origPathInfo = $this->getServer('ORIG_PATH_INFO');
        if (null !== $origPathInfo) {
            $queryString = $this->getServer('QUERY_STRING', '');
            if ('' !== $queryString) {
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
        $baseUrl = null;
        $filename = $this->getServer('SCRIPT_FILENAME', '');
        $scriptName = $this->getServer('SCRIPT_NAME');
        $phpSelf = $this->getServer('PHP_SELF');
        $origScriptName = $this->getServer('ORIG_SCRIPT_NAME');

        if (null !== $scriptName && basename($scriptName) === $filename) {
            $baseUrl = $scriptName;
        } elseif (null !== $phpSelf && basename($phpSelf) === $filename) {
            $baseUrl = $phpSelf;
        } elseif (null !== $origScriptName && basename($origScriptName) === $filename) {
            // 1and1 shared hosting compatibility.
            $baseUrl = $origScriptName;
        } else {
            // Backtrack up the SCRIPT_FILENAME to find the portion
            // matching PHP_SELF.

            $baseUrl = '/';
            $basename = basename($filename);
            if ($basename) {
                $path = ($phpSelf ? trim($phpSelf, '/') : '');
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
        if (
            strlen($requestUri) >= strlen($baseUrl)
            && (false !== ($pos = strpos($requestUri, $baseUrl)) && 0 !== $pos)
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

    /**
     * Removes extra keys in data
     */
    protected function removeExtraKeys()
    {
        foreach ($this->extraKeys as $offset => $value) {
            if (null === $this->data[$offset]) {
                unset($this->data[$offset]);
            }
        }
        $this->extraKeys = [];
    }
}
