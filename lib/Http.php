<?php

namespace Wei;

/**
 * An HTTP client that inspired by jQuery Ajax
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class Http extends Base implements \ArrayAccess, \Countable, \IteratorAggregate
{
    /**
     * The request URL
     *
     * @var string
     */
    protected $url;

    /**
     * The HTTP request method
     *
     * The method could be `GET`, `POST`, `DELETE`, `PUT`, `PATCH` or any other
     * methods that the server is supported
     *
     * @var string
     */
    protected $method = 'GET';

    /**
     * The content type in HTTP request header
     *
     * @var string
     */
    protected $contentType;

    /**
     * A key-value array to store cookies
     *
     * @var array
     */
    protected $cookies = array();

    /**
     * The data to send to the server
     *
     * @var array|string
     */
    protected $data = array();

    /**
     * Whether use the global options in `$wei->http` object when create a
     * new object
     *
     * @var bool
     */
    protected $global = false;

    /**
     * A key-value array to store request headers
     *
     * @var array
     */
    protected $headers = array();

    /**
     * Whether includes the header in the response string,
     * equals the CURLOPT_HEADER option
     * Set to true when you need to call getResponseHeaders, getResponseHeader,
     * getResponseCookies or getResponseCookie methods
     *
     * @var bool
     */
    protected $header = false;

    /**
     * The IP address for the host name in URL, NOT your client IP
     *
     * @var string
     */
    protected $ip;

    /**
     * A number of milliseconds to wait in the whole connection
     *
     * @var int
     */
    protected $timeout;

    /**
     * The data type to parse the response body
     *
     * The data type could by `json`, `jsonObject`, `xml`, `query`(URL query string),
     * `serialize` and `text`
     *
     * @var string
     */
    protected $dataType = 'text';

    /**
     * The custom HTTP referer string
     *
     * If set to true, it will use the request URL as referer string
     *
     * @var string|true
     */
    protected $referer;

    /**
     * The custom HTTP user agent string
     *
     * @var string
     */
    protected $userAgent;

    /**
     * Whether throw exception or keep silent when request error
     *
     * Note that the exception is thrown after triggered complete callback, rather than triggered error callback
     *
     * @var bool
     */
    protected $throwException = true;

    /**
     * A callback triggered after prepared the data and before the process the request
     *
     * @var callable
     */
    protected $beforeSend;

    /**
     * A callback triggered after the request is called success
     *
     * @var callable
     */
    protected $success;

    /**
     * A callback triggered when the request fails
     *
     * The `$textStatus` could be `curl`, `http`, and `parser`
     *
     * @var callable
     */
    protected $error;

    /**
     * A callback triggered when request finishes (after `success` and `error` callbacks are executed)
     *
     * @var callable
     */
    protected $complete;

    /**
     * The user define options for cURL handle
     *
     * @var array
     */
    protected $curlOptions = array();

    /**
     * The predefined options for cURL handle
     *
     * @var array
     */
    protected $defaultCurlOptions = array(
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
    );

    /**
     * The request result
     *
     * @var bool
     */
    protected $result;

    /**
     * The response body string
     *
     * @var string
     */
    protected $responseText;

    /**
     * The parsed response data
     *
     * @var mixed
     */
    protected $response;

    /**
     * The response header string
     *
     * @var string
     */
    protected $responseHeader;

    /**
     * The parsed response header array
     *
     * @var string
     */
    protected $responseHeaders;

    /**
     * A key-value array contains the response cookies
     *
     * @var array
     */
    protected $responseCookies;

    /**
     * The cURL session
     *
     * @var resource
     */
    protected $ch;

    /**
     * The error text status
     *
     * @var string
     */
    protected $errorStatus = '';

    /**
     * The error exception object
     *
     * @var \ErrorException
     */
    protected $errorException;

    /**
     * The default options of current object
     *
     * @var array
     */
    private $defaultOptions;

    /**
     * Constructor
     *
     * @param array $options
     */
    public function __construct(array $options = array())
    {
        // Merges options from default HTTP service
        if (isset($options['global']) && true == $options['global']) {
            $options += (array)$options['wei']->getConfig('http');
        }
        parent::__construct($options);
        $this->defaultOptions = $options;
    }

    /**
     * Create a new HTTP object and execute
     *
     * @param array|string $url A options array or the request URL
     * @param array $options A options array if the first parameter is string
     * @return $this A new HTTP object
     */
    public function __invoke($url = null, array $options = array())
    {
        // Merge and set options
        if (is_array($url)) {
            $options = $url;
        } else {
            $options['url'] = $url;
        }
        $options = $options + $this->defaultOptions;

        $http = new self($options);
        $http->execute();

        return $http;
    }

    /**
     * Execute the request, parse the response data and trigger relative callbacks
     */
    public function execute()
    {
        // Prepare request
        $ch = $this->ch = curl_init();
        curl_setopt_array($ch, $this->prepareCurlOptions());
        $this->beforeSend && call_user_func($this->beforeSend, $this, $ch);

        // Execute request
        $response = curl_exec($ch);

        // Handle response
        $this->handleResponse($response);
        $this->complete && call_user_func($this->complete, $this, $ch);

        if ($this->throwException && $this->errorException) {
            throw $this->errorException;
        }
    }

    /**
     * Prepare cURL options
     *
     * @return array
     */
    protected function prepareCurlOptions()
    {
        $opts = array();
        $url = $this->url;

        // CURLOPT_RESOLVE
        if ($this->ip) {
            $host = parse_url($url, PHP_URL_HOST);
            $url = substr_replace($url, $this->ip, strpos($url, $host), strlen($host));
            $this->headers['Host'] = $host;
        }

        switch ($this->method) {
            case 'GET' :
                $postData = false;
                break;

            case 'POST' :
                $postData = true;
                $opts[CURLOPT_POST] = 1;
                break;

            case 'DELETE':
            case 'PUT':
            case 'PATCH':
                $postData = true;
                $opts[CURLOPT_CUSTOMREQUEST] = $this->method;
                break;

            default:
                $postData = false;
                $opts[CURLOPT_CUSTOMREQUEST] = $this->method;
        }

        if ($this->data) {
            if ($postData) {
                $opts[CURLOPT_POSTFIELDS] = $this->data;
            } else {
                $data = is_string($this->data) ? $this->data : http_build_query($this->data);
                if (false === strpos($url, '?')) {
                    $url .= '?' . $data;
                } else {
                    $url .= '&' . $data;
                }
            }
        }

        if ($this->timeout > 0) {
            $opts[CURLOPT_TIMEOUT_MS] = $this->timeout;
        }

        if ($this->referer) {
            // Automatic use current request URL as referer URL
            if (true === $this->referer) {
                $opts[CURLOPT_REFERER] = $this->url;
            } else {
                $opts[CURLOPT_REFERER] = $this->referer;
            }
        }

        if ($this->userAgent) {
            $opts[CURLOPT_USERAGENT] = $this->userAgent;
        }

        if ($this->cookies) {
            $cookies = array();
            foreach ($this->cookies as $key => $value) {
                $cookies[] = $key . '=' . urlencode($value);
            }
            $opts[CURLOPT_COOKIE] = implode('; ', $cookies);
        }

        if ($this->contentType) {
            $this->headers['Content-Type'] = $this->contentType;
        }

        // Custom headers will overwrite other options
        if ($this->headers) {
            $headers = array();
            foreach ($this->headers as $key => $value) {
                $headers[] = $key . ': ' . $value;
            }
            $opts[CURLOPT_HTTPHEADER] = $headers;
        }

        $opts[CURLOPT_HEADER] = $this->header;
        $opts[CURLOPT_URL] = $url;

        $this->curlOptions += $opts + $this->defaultCurlOptions;
        return $this->curlOptions;
    }

    /**
     * Parse response text
     *
     * @param string $response
     */
    protected function handleResponse($response)
    {
        $ch = $this->ch;

        if (false !== $response) {
            $curlInfo = curl_getinfo($ch);

            // Parse response header
            if ($this->getCurlOption(CURLOPT_HEADER)) {
                // Fixes header size error when use CURLOPT_PROXY and CURLOPT_HTTPPROXYTUNNEL is true
                // http://sourceforge.net/p/curl/bugs/1204/
                if (false !== stripos($response, "HTTP/1.1 200 Connection established\r\n\r\n")) {
                    $response = str_ireplace("HTTP/1.1 200 Connection established\r\n\r\n", '', $response);
                }

                $this->responseHeader = trim(substr($response, 0, $curlInfo['header_size']));
                $this->responseText = substr($response, $curlInfo['header_size']);
            } else {
                $this->responseText = $response;
            }

            $statusCode = $curlInfo['http_code'];
            $isSuccess = $statusCode >= 200 && $statusCode < 300 || $statusCode === 304;
            if ($isSuccess) {
                $this->response = $this->parseResponse($this->responseText, $exception);
                if (!$exception) {
                    $this->result = true;
                    $this->success && call_user_func($this->success, $this->response, $this);
                } else {
                    $this->triggerError('parser', $exception);
                }
            } else {
                if ($this->responseHeader) {
                    preg_match('/[\d]{3} (.+?)\r/', $this->responseHeader, $matches);
                    $statusText = $matches[1];
                } else {
                    $statusText = 'HTTP request error';
                }
                $exception = new \ErrorException($statusText, $statusCode);
                $this->triggerError('http', $exception);
            }
        } else {
            $exception = new \ErrorException(curl_error($ch), curl_errno($ch));
            $this->triggerError('curl', $exception);
        }
    }

    /**
     * Trigger error callback
     *
     * @param string $status
     * @param \ErrorException $exception
     */
    protected function triggerError($status, \ErrorException $exception)
    {
        $this->result = false;
        $this->errorStatus = $status;
        $this->errorException = $exception;
        $this->error && call_user_func($this->error, $this, $status, $exception);
    }

    /**
     * Parse response data by specified type
     *
     * @param string $data
     * @param null $exception A variable to store exception when parsing error
     * @return mixed
     */
    protected function parseResponse($data, &$exception)
    {
        switch ($this->dataType) {
            case 'json' :
            case 'jsonObject' :
                $data = json_decode($data, $this->dataType === 'json');
                if (null === $data && json_last_error() != JSON_ERROR_NONE) {
                    $exception = new \ErrorException('JSON parsing error', json_last_error());
                }
                break;

            case 'xml' :
            case 'serialize' :
                $methods = array(
                    'xml' => 'simplexml_load_string',
                    'serialize' => 'unserialize',
                );
                $data = @$methods[$this->dataType]($data);
                if (false === $data && $e = error_get_last()) {
                    $exception = new \ErrorException($e['message'], $e['type'], 0, $e['file'], $e['line']);
                }
                break;

            case 'query' :
                // Parse $data(string) and assign the result to $data(array)
                parse_str($data, $data);
                break;

            case 'text':
            default :
                break;
        }
        return $data;
    }

    /**
     * Sets an option on the current cURL handle
     *
     * @param int $option
     * @param mixed $value
     * @return $this
     */
    public function setCurlOption($option, $value)
    {
        $this->curlOptions[$option] = $value;
        return $this;
    }

    /**
     * Returns an option value of the current cURL handle
     *
     * @param int $option
     * @return null
     */
    public function getCurlOption($option)
    {
        return isset($this->curlOptions[$option]) ? $this->curlOptions[$option] : null;
    }

    /**
     * Returns the response text
     *
     * @return string
     */
    public function getResponseText()
    {
        return $this->responseText;
    }

    /**
     * Returns the parsed response data
     *
     * @return mixed
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Returns the request URL
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Returns the request method
     *
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Returns the IP address for the host name in URL
     *
     * @return string
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * Returns the data to send to the server
     *
     * @return array|string
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Returns request header value
     *
     * @param string $name The header name
     * @param bool $first Return the first element or the whole header values
     * @return string|array When $first is true, returns string, otherwise, returns array
     */
    public function getResponseHeader($name = null, $first = true)
    {
        // Return response header string when parameter is not provided
        if (is_null($name)) {
            return $this->responseHeader;
        }

        $name = strtoupper($name);
        $headers = $this->getResponseHeaders();

        if (!isset($headers[$name])) {
            return $first ? null : array();
        } else {
            return $first ? current($headers[$name]) : $headers[$name];
        }
    }

    /**
     * Returns response headers array
     *
     * @return array
     */
    public function getResponseHeaders()
    {
        if (!is_array($this->responseHeaders)) {
            $this->responseHeaders = array();
            foreach (explode("\n", $this->responseHeader) as $line) {
                $line = explode(':', $line, 2);
                $name = strtoupper($line[0]);
                $value = isset($line[1]) ? trim($line[1]) : null;
                $this->responseHeaders[$name][] = $value;
            }
        }
        return $this->responseHeaders;
    }

    /**
     * Returns a key-value array contains the response cookies, like $_COOKIE
     *
     * @return array
     */
    public function getResponseCookies()
    {
        if (!is_array($this->responseCookies)) {
            $cookies = $this->getResponseHeader('SET-COOKIE', false);
            $this->responseCookies = array();
            foreach ($cookies as $cookie) {
                $this->responseCookies += $this->parseCookie($cookie);
            }
        }
        return $this->responseCookies;
    }

    /**
     * Returns the cookie value by specified name
     *
     * @param string $name
     * @return string|null
     */
    public function getResponseCookie($name)
    {
        $cookies = $this->getResponseCookies();
        return isset($cookies[$name]) ? $cookies[$name] : null;
    }

    /**
     * Parse cookie from header, returns result like $_COOKIE
     *
     * @param string $header
     * @return array
     */
    protected function parseCookie($header)
    {
        $elements = explode(';', $header);
        $cookies = array();

        $currentName = null;
        foreach ($elements as $element) {
            $pieces = explode('=', trim($element), 2);
            if (!isset($pieces[1])) {
                continue;
            }
            list($name, $value) = $pieces;

            if (strtolower($name) == 'expires' && strtotime($value) < time()) {
                // Removes expired cookie
                unset($cookies[$currentName]);
            } elseif (in_array(strtolower($name), array('domain', 'path', 'comment', 'expires', 'secure', 'max-age'))) {
                // Ignore cookie attribute
                continue;
            } else {
                $cookies[$name] = trim(urldecode($value));
                $currentName = $name;
            }
        }
        return $cookies;
    }

    /**
     * Returns if the request is success
     *
     * @return bool
     */
    public function isSuccess()
    {
        return $this->result;
    }

    /**
     * Set request method
     *
     * @param string $method
     * @return $this
     */
    public function setMethod($method)
    {
        $this->method = strtoupper($method);
        return $this;
    }

    /**
     * Execute a GET method request and parser response data to JSON array
     *
     * @param string $url
     * @param array $data
     * @return $this
     */
    public function getJson($url, $data = array())
    {
        return $this->processMethod($url, $data, 'json', 'GET');
    }

    /**
     * Execute a GET method request and parser response data to JSON object
     *
     * @param string $url
     * @param array $data
     * @return $this
     */
    public function getJsonObject($url, $data = array())
    {
        return $this->processMethod($url, $data, 'jsonObject', 'GET');
    }

    /**
     * Execute a GET method request
     *
     * @param string $url
     * @param array $data
     * @param string $dataType
     * @return $this
     */
    public function get($url, $data = array(), $dataType = null)
    {
        return $this->processMethod($url, $data, $dataType, 'GET');
    }

    /**
     * Execute a POST method request
     *
     * @param string $url
     * @param array $data
     * @param string $dataType
     * @return $this
     */
    public function post($url, $data = array(), $dataType = null)
    {
        return $this->processMethod($url, $data, $dataType, 'POST');
    }

    /**
     * Execute a PUT method request
     *
     * @param string $url
     * @param array $data
     * @param string $dataType
     * @return $this
     */
    public function put($url, $data = array(), $dataType = null)
    {
        return $this->processMethod($url, $data, $dataType, 'PUT');
    }

    /**
     * Execute a DELETE method request
     *
     * @param string $url
     * @param array $data
     * @param string $dataType
     * @return $this
     */
    public function delete($url, $data = array(), $dataType = null)
    {
        return $this->processMethod($url, $data, $dataType, 'DELETE');
    }

    /**
     * Execute a PATCH method request
     *
     * @param string $url
     * @param array $data
     * @param string $dataType
     * @return $this
     */
    public function patch($url, $data = array(), $dataType = null)
    {
        return $this->processMethod($url, $data, $dataType, 'PATCH');
    }

    /**
     * Execute a specified method request
     *
     * @param string $url
     * @param array $data
     * @param string $dataType
     * @param string $method
     * @return $this
     */
    protected function processMethod($url, $data, $dataType, $method)
    {
        return $this->__invoke(array(
            'url' => $url,
            'method' => $method,
            'dataType' => $dataType,
            'data' => $data
        ));
    }

    /**
     * Returns the error status text
     *
     * @return string
     */
    public function getErrorStatus()
    {
        return $this->errorStatus;
    }

    /**
     * Returns the error exception object
     *
     * @return \ErrorException
     */
    public function getErrorException()
    {
        return $this->errorException;
    }

    /**
     * Check if the offset exists
     *
     * @param  string $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->response);
    }

    /**
     * Get the offset value
     *
     * @param  string $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return isset($this->response[$offset]) ? $this->response[$offset] : null;
    }

    /**
     * Set the offset value
     *
     * @param string $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        $this->response[$offset] = $value;
    }

    /**
     * Unset the offset
     *
     * @param string $offset
     */
    public function offsetUnset($offset)
    {
        unset($this->response[$offset]);
    }

    /**
     * Return the length of data
     *
     * @return int the length of data
     */
    public function count()
    {
        return count($this->response);
    }

    /**
     * Retrieve an array iterator
     *
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->response);
    }

    /**
     * Returns to response body string
     *
     * @return string
     */
    public function __toString()
    {
        return (string)$this->responseText;
    }

    /**
     * @return array
     */
    public function getCurlInfo()
    {
        return curl_getinfo($this->ch);
    }

    /**
     * Close the cURL session
     */
    public function __destruct()
    {
        if ($this->ch) {
            curl_close($this->ch);
            unset($this->ch);
        }
    }
}