<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Widget;

/**
 * A widget handles HTTP request which inspired by jQuery Ajax
 *
 * @author      Twin Huang <twinhuang@qq.com>
 * @link        http://api.jquery.com/jQuery.ajax/
 */
class Call extends Base
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
    protected $contentType = 'application/x-www-form-urlencoded; charset=UTF-8';

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
     * Whether use the global options in `$widget->call` object when create a
     * new object
     *
     * @var bool
     */
    protected $global = true;

    /**
     * A key-value array to store request headers
     *
     * @var array
     */
    protected $headers = array();

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
     * @var string
     */
    protected $referer;

    /**
     * The custom HTTP user agent string
     *
     * @var string
     */
    protected $userAgent;

    /**
     * Whether disable cURL from verifying the peer's certificate
     *
     * @var bool
     * @link http://unitstep.net/blog/2009/05/05/using-curl-in-php-to-access-https-ssltls-protected-sites/
     * @link http://stackoverflow.com/questions/6400300/php-curl-https-causing-exception-ssl-certificate-problem-verify-that-the-ca-cer
     */
    protected $disableSslVerification = false;

    /**
     * An event triggered after prepared the data and before the process the request
     *
     * ```php
     * $widget->call(array(
     *     'beforeSend' => function(Call $call, $ch){
     *
     *     }
     * ));
     * ```
     *
     * @var callback
     */
    protected $beforeSend;

    /**
     * An event triggered after the request is called success
     *
     * ```php
     * $widget->call(array(
     *     'success' => function($data, Call $call){
     *
     *     }
     * ));
     * ```
     *
     * @var callback
     */
    protected $success;

    /**
     * An event triggered when the request fails
     *
     * The `$textStatus` could be `curl`, `http`, and `parser`
     *
     * ```php
     * $widget->call(array(
     *     'error' => function(Call $call, $textStatus, \ErrorException $exception){
     *
     *     }
     * ));
     * ```
     *
     * @var callback
     */
    protected $error;

    /**
     * An event triggered when finishes (after `success` and `error` callbacks are executed)
     *
     * ```php
     * $widget->call(array(
     *     'complete' => function(Call $call, $ch){
     *
     *     }
     * ));
     * ```
     *
     * @var callback
     */
    protected $complete;

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
     * The parsed response JSON array
     *
     * @var array
     */
    protected $responseJson;

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
     * The predefined cURL options
     *
     * @var array
     */
    protected $curlOpts = array(
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HEADER => true,
        CURLOPT_FOLLOWLOCATION => true,
    );

    /**
     * Create a new call object and execute
     *
     * @param array|string $url A options array or the request URL
     * @param array $options A options array if the first parameter is string
     * @return Call A new call object
     */
    public function __invoke($url = null, array $options = array())
    {
        // Merge and set options
        if (is_array($url)) {
            $options = $url;
        } else {
            $options['url'] = $url;
        }

        // Merge options from parent options
        if (!isset($options['global']) || $options['global'] === true) {
            $options += get_object_vars($this);
        } else {
            $options = array(
                'widget' => $this->widget,
                'global' => false
            ) + $options;
        }

        $call = new self($options);

        $call->execute();

        return $call;
    }

    /**
     * Execute the request, parse the response data and trigger relative events
     */
    public function execute()
    {
        $ch = $this->ch = curl_init();

        curl_setopt_array($ch, $this->prepareCurlOpts());

        $this->beforeSend && call_user_func($this->beforeSend, $this, $ch);

        $response = curl_exec($ch);

        $this->handleResponse($response);

        $this->complete && call_user_func($this->complete, $this, $ch);

        curl_close($ch);
    }

    /**
     * Prepare cURL options
     *
     * @return array
     */
    protected function prepareCurlOpts()
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
            $data = is_string($this->data) ? $this->data : http_build_query($this->data);
            if ($postData) {
                $opts[CURLOPT_POSTFIELDS] = $data;
            } else {
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
            foreach($this->cookies as $key => $value) {
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

        if ($this->disableSslVerification) {
            $opts[CURLOPT_SSL_VERIFYPEER] = false;
        }

        $opts[CURLOPT_URL] = $url;

        return $this->curlOpts + $opts;
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
            // Split to two parts: header and body
            list($this->responseHeader, $this->responseText) = explode("\r\n\r\n", $response, 2);
            $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $isSuccess = $statusCode >= 200 && $statusCode < 300 || $statusCode === 304;
            if ($isSuccess) {
                $this->response = $response = $this->parse($this->responseText, $this->dataType, $exception);
                if (!$exception) {
                    $this->result = true;
                    $property = 'response' . ucfirst($this->dataType);
                    if (property_exists($this, $property)) {
                        $this->$property = $response;
                    }
                    $this->success && call_user_func($this->success, $response, $this);
                } else {
                    $this->result = false;
                    $this->triggerError('parser', $exception);
                }
            } else {
                preg_match('/[\d]{3} (.+?)\r/', $this->responseHeader, $matches);
                $exception = new \ErrorException($matches[1], $statusCode);
                $this->result = false;
                $this->triggerError('http', $exception);
            }
        } else {
            $exception = new \ErrorException(curl_error($ch), curl_errno($ch));
            $this->result = false;
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
        $this->errorStatus = $status;
        $this->errorException = $exception;
        $this->error && call_user_func($this->error, $this, $status, $exception);
    }

    /**
     * Parse data by specified type
     *
     * @param string $data
     * @param string $type
     * @param null $exception A variable to store exception when parser error
     * @return mixed
     */
    protected function parse($data, $type, &$exception)
    {
        switch ($type) {
            case 'json' :
                $data = json_decode($data, true);
                if (null === $data && json_last_error() != JSON_ERROR_NONE) {
                    $exception = new \ErrorException('Parser error', json_last_error());
                }
                break;

            case 'jsonObject' :
                $data = json_decode($data);
                if (null === $data && json_last_error() != JSON_ERROR_NONE) {
                    $exception = new \ErrorException('Parser error', json_last_error());
                }
                break;

            case 'xml' :
                $data = @simplexml_load_string($data);
                if (false === $data && $e = error_get_last()) {
                    $exception = new \ErrorException($e['message'], $e['type'], 0, $e['file'], $e['line']);
                }
                break;

            case 'query' :
                // Parse $data(string) and assign the result to $data(array)
                parse_str($data, $data);
                break;

            case 'serialize' :
                $data = @unserialize($data);
                if (false === $data && $e = error_get_last()) {
                    $exception = new \ErrorException($e['message'], $e['type'], 0, $e['file'], $e['line']);
                }
                break;

            case 'text':
            default :
                break;
        }

        return $data;
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
     * Returns the response json data
     *
     * @return array
     */
    public function getResponseJson()
    {
        return $this->responseJson;
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
     * Set request header value
     *
     * @param string $name
     * @param string $value
     * @return Call
     */
    public function setRequestHeader($name, $value)
    {
        $this->headers[$name] = $value;

        return $this;
    }

    /**
     * Returns request header value
     *
     * @param string $name
     * @return string
     */
    public function getResponseHeader($name)
    {
        if (!is_array($this->responseHeaders)) {
            if ($this->responseHeader) {
                $this->responseHeaders = $this->parseHeader($this->responseHeader);
            } else {
                $this->responseHeaders = array();
            }
        }
        $name = strtoupper($name);
        return isset($this->responseHeaders[$name]) ? $this->responseHeaders[$name] : null;
    }

    /**
     * Parse the HTTP response header to key-value array
     *
     * @param string $header
     * @return array
     */
    protected function parseHeader($header)
    {
        $headers = array();
        foreach (explode("\n", $header) as $line) {
            $line = explode(':', $line, 2);
            if (isset($line[1])) {
                $headers[strtoupper($line[0])] = trim($line[1]);
            }
        }

        return $headers;
    }

    /**
     * Return if the request is success
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
     * @return Call
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
     * @param callback $callback
     * @return Call
     */
    public function getJson($url, $data, $callback = null)
    {
        return $this->processMethod($url, $data, $callback, 'json', 'GET');
    }

    /**
     * Execute a GET method request and parser response data to JSON object
     *
     * @param string $url
     * @param array $data
     * @param callback $callback
     * @return Call
     */
    public function getJsonObject($url, $data, $callback = null)
    {
        return $this->processMethod($url, $data, $callback, 'jsonObject', 'GET');
    }

    /**
     * Execute a GET method request
     *
     * @param string $url
     * @param array $data
     * @param callback $callback
     * @param string $dataType
     * @return Call
     */
    public function get($url, $data, $callback = null, $dataType = null)
    {
        return $this->processMethod($url, $data, $callback, $dataType, 'GET');
    }

    /**
     * Execute a POST method request
     *
     * @param string $url
     * @param array $data
     * @param callback $callback
     * @param string $dataType
     * @return Call
     */
    public function post($url, $data, $callback = null, $dataType = null)
    {
        return $this->processMethod($url, $data, $callback, $dataType, 'POST');
    }

    /**
     * Execute a PUT method request
     *
     * @param string $url
     * @param array $data
     * @param callback $callback
     * @param string $dataType
     * @return Call
     */
    public function put($url, $data, $callback = null, $dataType = null)
    {
        return $this->processMethod($url, $data, $callback, $dataType, 'PUT');
    }

    /**
     * Execute a DELETE method request
     *
     * @param string $url
     * @param array $data
     * @param callback $callback
     * @param string $dataType
     * @return Call
     */
    public function delete($url, $data, $callback = null, $dataType = null)
    {
        return $this->processMethod($url, $data, $callback, $dataType, 'DELETE');
    }

    /**
     * Execute a PATCH method request
     *
     * @param string $url
     * @param array $data
     * @param callback $callback
     * @param string $dataType
     * @return Call
     */
    public function patch($url, $data, $callback = null, $dataType = null)
    {
        return $this->processMethod($url, $data, $callback, $dataType, 'PATCH');
    }

    /**
     * Execute a specified method request
     *
     * @param string $url
     * @param array $data
     * @param callback $callback
     * @param string $dataType
     * @param string $method
     * @return Call
     */
    protected function processMethod($url, $data, $callback, $dataType, $method)
    {
        if (is_callable($data)) {
            $dataType = $dataType ?: $callback;
            $callback = $data;
            $data = array();
        }

        return $this(array(
            'url' => $url,
            'method' => $method,
            'dataType' => $dataType,
            'data' => $data,
            'success' => $callback
        ));
    }

    /**
     * Set callback for success event
     *
     * @param \Closure $fn
     * @return Call
     */
    public function success(\Closure $fn)
    {
        $this->success = $fn;
        return $this;
    }

    /**
     * Set callback for error event
     *
     * @param \Closure $fn
     * @return Call
     */
    public function error(\Closure $fn)
    {
        $this->error = $fn;
        return $this;
    }

    /**
     * Set callback for complete event
     *
     * @param \Closure $fn
     * @return Call
     */
    public function complete(\Closure $fn)
    {
        $this->complete = $fn;
        return $this;
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
}