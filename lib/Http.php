<?php

/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2022 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

/**
 * An HTTP client that inspired by jQuery Ajax
 *
 * @author      Twin Huang <twinhuang@qq.com>
 * @SuppressWarnings(PHPMD.ShortVariable)
 * @mixin \RetMixin
 */
class Http extends Base implements \ArrayAccess, \Countable, \IteratorAggregate
{
    private const MIN_SUCCESSFUL = 200;

    private const MAX_SUCCESSFUL = 299;

    private const NOT_MODIFIED = 304;

    /**
     * {@inheritdoc}
     */
    protected static $createNewInstance = true;

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
    protected $cookies = [];

    /**
     * The data append to the URL
     *
     * @var array|string
     */
    protected $params = [];

    /**
     * The data to send to the server
     *
     * @var array|string
     */
    protected $data = [];

    /**
     * The json data to send to the server
     *
     * @var array
     */
    protected $json = [];

    /**
     * The files send to the server
     *
     * @var array
     */
    protected $files = [];

    /**
     * A key-value array to store request headers
     *
     * @var array
     */
    protected $headers = [];

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
     * @var string|null
     */
    protected $dataType;

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
     * The times to retry request if response is error
     *
     * @var int
     */
    protected $retries = 0;

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
    protected $curlOptions = [];

    /**
     * The predefined options for cURL handle
     *
     * @var array
     */
    protected $defaultCurlOptions = [
        \CURLOPT_RETURNTRANSFER => true,
        \CURLOPT_FOLLOWLOCATION => true,
    ];

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
     * The times left to retry request if response is error
     *
     * @var int|null
     */
    protected $leftRetries;

    /**
     * Close the cURL session
     */
    public function __destruct()
    {
        if ($this->ch) {
            curl_close($this->ch);
            $this->ch = null;
        }
    }

    /**
     * Execute the request
     *
     * @param array|string|null $url A options array or the request URL
     * @param array $options A options array if the first parameter is string
     * @return $this A new HTTP object
     */
    public function __invoke($url = null, array $options = [])
    {
        // Merge and set options
        if (is_array($url)) {
            $options = $url;
        } elseif ($url) {
            $options['url'] = $url;
        }
        $this->setOption($options);
        $this->execute();
        return $this;
    }

    /**
     * Returns to response body string
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->responseText;
    }

    /**
     * Execute the request, parse the response data and trigger relative callbacks
     */
    public function execute()
    {
        // Init the retry times
        if ($this->retries && null === $this->leftRetries) {
            $this->leftRetries = $this->retries;
        }

        // Prepare request
        $handle = $this->ch = curl_init();
        curl_setopt_array($handle, $this->prepareCurlOptions());
        $this->beforeSend && call_user_func($this->beforeSend, $this, $handle);

        // Execute request
        $response = curl_exec($handle);

        // Handle response
        $this->handleResponse($response);
        $this->complete && call_user_func($this->complete, $this, $handle);

        // Retry if response error
        if (false === $this->result && $this->leftRetries > 0) {
            --$this->leftRetries;
            $this->execute();
            return;
        }

        if ($this->throwException && $this->errorException) {
            throw $this->errorException;
        }
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
        if (null === $name) {
            return $this->responseHeader;
        }

        $name = strtoupper($name);
        $headers = $this->getResponseHeaders();

        if (!isset($headers[$name])) {
            return $first ? null : [];
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
            $this->responseHeaders = [];
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
            $this->responseCookies = [];
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
    public function getJson($url, $data = [])
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
    public function getJsonObject($url, $data = [])
    {
        return $this->processMethod($url, $data, 'jsonObject', 'GET');
    }

    /**
     * Execute a POST method request and parser response data to JSON array
     *
     * @param string $url
     * @param array $data
     * @return $this
     */
    public function postJson($url, $data = [])
    {
        return $this->processMethod($url, $data, 'json', 'POST');
    }

    public function upload($url, $data = [], $dataType = null)
    {
        return $this->processMethod($url, $data, $dataType, 'POST');
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
     * @param string $offset
     * @return bool
     */
    public function offsetExists($offset): bool
    {
        return is_array($this->response) && array_key_exists($offset, $this->response);
    }

    /**
     * Get the offset value
     *
     * @param string $offset
     * @return mixed
     */
    #[\ReturnTypeWillChange]
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
    public function offsetSet($offset, $value): void
    {
        $this->response[$offset] = $value;
    }

    /**
     * Unset the offset
     *
     * @param string $offset
     */
    public function offsetUnset($offset): void
    {
        unset($this->response[$offset]);
    }

    /**
     * Return the length of data
     *
     * @return int the length of data
     */
    public function count(): int
    {
        return count($this->response);
    }

    /**
     * Retrieve an array iterator
     *
     * @return \ArrayIterator
     */
    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->response);
    }

    /**
     * Get information from curl
     *
     * @param int|null $option
     * @return mixed
     */
    public function getCurlInfo($option = null)
    {
        return $option ? curl_getinfo($this->ch, $option) : curl_getinfo($this->ch);
    }

    /**
     * Convert the HTTP response to a Ret object
     *
     * @param array $data
     * @return Ret
     */
    public function toRet(array $data = [])
    {
        if ($this->isSuccess()) {
            $response = $this->getResponse();
            $extra = is_array($response) ? $response : ['data' => $response];
            return $this->ret->suc($data + $extra);
        }

        $e = $this->getErrorException();
        return $this->ret->err($data + ['code' => $e->getCode(), 'message' => $e->getMessage()]);
    }

    /**
     * Set request method
     *
     * @param string $method
     * @return $this
     */
    public function method(string $method): self
    {
        $this->setMethod($method);
        return $this;
    }

    /**
     * Set the data to send to the server
     *
     * @param array|string $data
     * @return $this
     */
    public function data($data): self
    {
        $this->data = $data;
        return $this;
    }

    /**
     * The json data to send to the server
     *
     * @param array|\JsonSerializable $json
     * @return $this
     */
    public function json($json): self
    {
        $this->json = $json;
        return $this;
    }

    /**
     * Set the data append to the URL
     *
     * @param array|string $params
     * @return $this
     */
    public function params($params): self
    {
        $this->params = $params;
        return $this;
    }

    /**
     * Set URL of the current request
     *
     * @param string $url
     * @return $this
     * @svc
     */
    protected function url(string $url): self
    {
        $this->url = $url;
        return $this;
    }

    /**
     * Create a new HTTP object and execute
     *
     * @param array|string|null $url A options array or the request URL
     * @param array $options A options array if the first parameter is string
     * @return $this A new HTTP object
     * @svc
     */
    protected function request($url = null, array $options = []): self
    {
        return $this->__invoke($url, $options);
    }

    /**
     * Execute a GET method request
     *
     * @param string|array|null $url
     * @param array $options
     * @return $this
     * @svc
     */
    protected function get($url = null, array $options = []): self
    {
        return $this->request($url, $options);
    }

    /**
     * Execute a POST method request
     *
     * @param string|array|null $url
     * @param array $options
     * @return $this
     * @svc
     */
    protected function post($url = null, array $options = []): self
    {
        return $this->setMethod('POST')->request($url, $options);
    }

    /**
     * Execute a PUT method request
     *
     * @param string|array|null $url
     * @param array $options
     * @return $this
     * @svc
     */
    protected function put($url = null, array $options = []): self
    {
        return $this->setMethod('PUT')->request($url, $options);
    }

    /**
     * Execute a DELETE method request
     *
     * @param string|array|null $url
     * @param array $options
     * @return $this
     * @svc
     */
    protected function delete($url = null, array $options = []): self
    {
        return $this->setMethod('DELETE')->request($url, $options);
    }

    /**
     * Execute a PATCH method request
     *
     * @param string|array|null $url
     * @param array $options
     * @return $this
     * @svc
     */
    protected function patch($url = null, array $options = []): self
    {
        return $this->setMethod('PATCH')->request($url, $options);
    }

    /**
     * Create a new HTTP object and execute, return a Ret object
     *
     * @param array|string $url A options array or the request URL
     * @param array $options A options array if the first parameter is string
     * @return Ret
     * @svc
     */
    protected function requestRet($url = null, array $options = []): Ret
    {
        return $this->__invoke($url, $options)->toRet();
    }

    /**
     * Prepare cURL options
     *
     * @return array
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @todo split to small methods
     */
    protected function prepareCurlOptions()
    {
        $opts = [];
        $url = $this->url;

        // CURLOPT_RESOLVE
        if ($this->ip) {
            $host = parse_url($url, \PHP_URL_HOST);
            $url = substr_replace($url, $this->ip, strpos($url, $host), strlen($host));
            $this->headers['Host'] = $host;
        }

        switch ($this->method) {
            case 'GET':
                $postData = false;
                break;

            case 'POST':
                $postData = true;
                $opts[\CURLOPT_POST] = 1;
                break;

            case 'DELETE':
            case 'PUT':
            case 'PATCH':
                $postData = true;
                $opts[\CURLOPT_CUSTOMREQUEST] = $this->method;
                break;

            default:
                $postData = false;
                $opts[\CURLOPT_CUSTOMREQUEST] = $this->method;
        }

        if ($this->params) {
            $url = $this->appendParams($url, $this->params);
        }

        if ($this->data) {
            $data = is_string($this->data) ? $this->data : http_build_query($this->data);
            if ($postData) {
                $opts[\CURLOPT_POSTFIELDS] = $data;
            } else {
                $url = $this->appendParams($url, $data);
            }
        }

        if ($this->json) {
            $opts[\CURLOPT_POSTFIELDS] = json_encode($this->json, \JSON_UNESCAPED_SLASHES | \JSON_UNESCAPED_UNICODE);
            $this->headers['Content-Type'] = 'application/json';
        }

        if ($this->files) {
            $postFields = isset($opts[\CURLOPT_POSTFIELDS]) ? $opts[\CURLOPT_POSTFIELDS] : '';
            $opts[\CURLOPT_POSTFIELDS] = $this->addFileField($postFields, $this->files);
        }

        if ($this->timeout > 0) {
            $opts[\CURLOPT_TIMEOUT_MS] = $this->timeout;
        }

        if ($this->referer) {
            // Automatic use current request URL as referer URL
            if (true === $this->referer) {
                $opts[\CURLOPT_REFERER] = $this->url;
            } else {
                $opts[\CURLOPT_REFERER] = $this->referer;
            }
        }

        if ($this->userAgent) {
            $opts[\CURLOPT_USERAGENT] = $this->userAgent;
        }

        if ($this->cookies) {
            $cookies = [];
            foreach ($this->cookies as $key => $value) {
                $cookies[] = $key . '=' . rawurlencode($value);
            }
            $opts[\CURLOPT_COOKIE] = implode('; ', $cookies);
        }

        if ($this->contentType) {
            $this->headers['Content-Type'] = $this->contentType;
        }

        // Custom headers will overwrite other options
        if ($this->headers) {
            $headers = [];
            foreach ($this->headers as $key => $value) {
                $headers[] = $key . ': ' . $value;
            }
            $opts[\CURLOPT_HTTPHEADER] = $headers;
        }

        $opts[\CURLOPT_HEADER] = $this->header;
        $opts[\CURLOPT_URL] = $url;

        $this->curlOptions += $opts + $this->defaultCurlOptions;
        return $this->curlOptions;
    }

    /**
     * @param string $data
     * @param array $files
     * @return array
     */
    protected function addFileField($data, array $files)
    {
        $newData = [];
        if ($data) {
            foreach (explode('&', $data) as $key => $value) {
                list($key, $value) = explode('=', urldecode($value));
                $newData[$key] = $value;
            }
        }
        $hasCurlFile = class_exists('CURLFile');
        foreach ($files as $name => $file) {
            $newData[$name] = $hasCurlFile ? new \CURLFile($file) : '@' . $file;
        }
        return $newData;
    }

    /**
     * Parse response text
     *
     * @param string $response
     */
    protected function handleResponse($response)
    {
        $handle = $this->ch;

        if (false !== $response) {
            $curlInfo = curl_getinfo($handle);

            // Parse response header
            if ($this->getCurlOption(\CURLOPT_HEADER)) {
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
            $isSuccess = $statusCode >= self::MIN_SUCCESSFUL
                && $statusCode <= self::MAX_SUCCESSFUL
                || self::NOT_MODIFIED === $statusCode;
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
                // + 1000 to avoid conflicts with error service 404 detection
                $exception = new \ErrorException($statusText, $statusCode + 1000);
                $this->triggerError('http', $exception);
            }
        } else {
            $exception = new \ErrorException(curl_error($handle), curl_errno($handle));
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
        $dataType = $this->dataType;
        if (!$dataType && 'application/json' === $this->getResponseHeader('CONTENT-TYPE')) {
            $dataType = 'json';
        }

        switch ($dataType) {
            case 'json':
            case 'jsonObject':
                $result = json_decode($data, 'json' === $dataType);
                if (null === $result && \JSON_ERROR_NONE != json_last_error()) {
                    $exception = new \ErrorException('JSON parsing error, the data is ' . $data, json_last_error());
                }
                break;

            case 'xml':
            case 'serialize':
                $methods = [
                    'xml' => 'simplexml_load_string',
                    'serialize' => 'unserialize',
                ];
                // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
                $result = @$methods[$this->dataType]($data);
                if (false === $result && $e = error_get_last()) {
                    $exception = new \ErrorException($e['message'], $e['type'], 0, $e['file'], $e['line']);
                }
                break;

            case 'query':
                // Parse $data(string) and assign the result to $result(array)
                parse_str($data, $result);
                break;

            case 'text':
            default:
                $result = $data;
                break;
        }
        return $result;
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
        $cookies = [];

        $currentName = null;
        foreach ($elements as $element) {
            $pieces = explode('=', trim($element), 2);
            if (!isset($pieces[1])) {
                continue;
            }
            list($name, $value) = $pieces;
            $lowerName = strtolower($name);

            if ('expires' === $lowerName && strtotime($value) < time()) {
                // Removes expired cookie
                unset($cookies[$currentName]);
            } elseif (in_array($lowerName, ['domain', 'path', 'comment', 'expires', 'secure', 'max-age'], true)) {
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
        return $this->__invoke([
            'url' => $url,
            'method' => $method,
            'dataType' => $dataType,
            'data' => $data,
        ]);
    }

    /**
     * @param string $url
     * @param array|string $params
     * @return string
     */
    protected function appendParams(string $url, $params): string
    {
        if (!is_string($params)) {
            $params = http_build_query($params);
        }
        return $url . ((false === strpos($url, '?')) ? '?' : '&') . $params;
    }
}
