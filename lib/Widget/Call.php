<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Widget;

/**
 * A widget handles API request which inspired jQuery Ajax
 *
 * @author      Twin Huang <twinh@yahoo.cn>
 * @link        http://api.jquery.com/jQuery.ajax/
 */
class Call extends AbstractWidget
{
    protected $method = 'GET';

    protected $cache;

    protected $contentType = 'application/x-www-form-urlencoded; charset=UTF-8';

    protected $cookies = array();

    /**
     *
     * @var array
     */
    protected $data = array();

    /**
     * The request header
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

    protected $time;

    protected $timeout;

    protected $dataType = 'json';

    protected $referer;

    protected $userAgent;

    /**
     * An event triggered after prepared the data and before the process the request
     *
     * @var callback
     */
    protected $beforeSend;

    /**
     * An event triggered after the request is called success
     *
     * @var callback
     */
    protected $success;

    /**
     * An event triggered when the requeset fails
     *
     * ```php
     * $widget->call(array(
     *     'error' => function($call, $textStatus, $message){
     *
     *     }
     * ));
     *
     * @var callback
     */
    protected $error;

    /**
     * An event triggered when finishes (after `success` and `error` callbacks are executed)
     *
     * ```php
     * $widget->call(array(
     *     'complete' => function($call){
     *
     *     }
     * ));
     * ```
     *
     * @var callback
     */
    protected $complete;

    protected $statusText = 'success';

    /**
     * The response body string
     *
     * @var string
     */
    protected $responseText;

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

    public function __invoke($url = null, array $options = array())
    {
        // Merge and set options
        if (is_array($url)) {
            $options = $url;
        } else {
            $options['url'] = $url;
        }

        $call = new self(array('widget' => $this->widget) + $options);

        $call->execute();

        return $call;
    }

    public function execute()
    {
        $ch = curl_init();
        $opts = array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => true,
            CURLOPT_URL => $this->url
        );

        $this->method = strtoupper($this->method);
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
            $data = http_build_query($this->data);
            if ($postData) {
                $opts[CURLOPT_POSTFIELDS] = $data;
            } else {
                if (false === strpos($this->url, '?')) {
                    $opts[CURLOPT_URL] = $this->url . '?' . $data;
                } else {
                    $opts[CURLOPT_URL] = $this->url . '&' . $data;
                }
            }
        }

        if ($this->timeout >= 0) {
            $opts[CURLOPT_TIMEOUT_MS] = $this->timeout;
        }

        if ($this->referer) {
            $opts[CURLOPT_REFERER] = $this->referer;
        }

        if ($this->userAgent) {
            $opts[CURLOPT_USERAGENT] = $this->userAgent;
        }

        if ($this->cookies) {
            foreach($this->cookies as $key => $value) {
                $cookies[] = $key . '=' . urlencode($value);
            }
            $opts[CURLOPT_COOKIE] = implode('; ', $cookies);
        }

        if ($this->contentType) {
            $this->headers['Content-Type'] = $this->contentType;
        }

        // CURLOPT_RESOLVE
        if ($this->ip) {
            $host = parse_url($this->url, PHP_URL_HOST);
            $this->url = substr_replace($this->url, $this->ip, strpos($this->url, $host), strlen($host));
            $this->headers['Host'] = $host;
        }

        // Custom headers will overwrite other options
        if ($this->headers) {
            $headers = array();
            foreach ($this->headers as $key => $value) {
                $headers[] = $key . ': ' . $value;
            }
            $opts[CURLOPT_HTTPHEADER] = $headers;
        }

        curl_setopt_array($ch, $opts);
        $this->trigger('beforeSend', array($this, $ch));
        $response = curl_exec($ch);

        if (false !== $response) {
            list($this->responseHeader, $this->responseText) = explode("\r\n\r\n", $response, 2);
            $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $isSuccess = $statusCode >= 200 && $statusCode < 300 || $statusCode === 304;
            if ($isSuccess) {
                $this->handleResponse($this->responseText, $ch);
            } else {
                $this->trigger('error', array($this, 'stateCode', curl_error($ch)));
            }
        } else {
            $this->trigger('error', array($this, 'curl', curl_error($ch)));
        }

        curl_close($ch);
        $this->trigger('complete', array($this));
    }

    protected function handleResponse($response, $object)
    {
        $response = $this->decode($response, $this->dataType);
        if ('success' != $response['state']) {
            $this->trigger('error', array($this, $response['state'], $response['error']));
        } else {
            $this->trigger('success', array($response['data'], $this));
        }
    }

    protected function decode($data, $type)
    {
        switch ($type) {
            case 'json' :
                $data = json_decode($data);
                if (null === $data && json_last_error() != JSON_ERROR_NONE) {
                    return array('state' => 'parsererror', 'error' => json_last_error());
                }
                return array('state' => 'success', 'data' => $data);


            case 'xml' :
                $data = @simplexml_load_string($data);
                if (false === $data) {
                    return array('state' => 'parsererror', 'data' => $data, 'error' => $this->createErrorException());
                } else {
                    return array('state' => 'success', 'data' => $data);
                }

            case 'query' :
                $output = array();
                parse_str($data, $output);
                return array('state' => 'success', 'data' => $output);

            case 'serialize' :
                $data = @unserialize($data);
                if (error_get_last()) {
                    return array('state' => 'parsererror', 'data' => false, 'error' => $this->createErrorException());
                } else {
                    return array('state' => 'success', 'data' => $data);
                }

            case 'text':
            case 'raw' :
            default :
                return array('state' => 'success', 'data' => $data);
        }
    }

    protected function trigger($name, $params = array())
    {
        if (is_callable($this->$name)) {
            $params = is_array($params) ? $params : array($params);
            call_user_func_array($this->$name, $params);
        }
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

    public function setRequestHeader($name, $value)
    {
        $this->headers[$name] = $value;

        return $this;
    }

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

    public function parseHeader($header)
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

    public function get($url, $data, $callback = null)
    {
        return $this->processMethod($url, $data, $callback, 'GET');
    }

    public function post($url, $data, $callback = null)
    {
        return $this->processMethod($url, $data, $callback, 'POST');
    }

    public function put($url, $data, $callback = null)
    {
        return $this->processMethod($url, $data, $callback, 'PUT');
    }

    public function delete($url, $data, $callback = null)
    {
        return $this->processMethod($url, $data, $callback, 'DELETE');
    }

    public function patch($url, $data, $callback = null)
    {
        return $this->processMethod($url, $data, $callback, 'PATCH');
    }

    protected function processMethod($url, $data, $callback, $method)
    {
        if (is_callable($data)) {
            $callback = $data;
            $data = array();
        }

        return $this(array(
            'url' => $url,
            'method' => $method,
            'data' => $data,
            'success' => $callback
        ));
    }

    /**
     * Set callback for success event
     *
     * @param \Closure $fn
     * @return \Widget\Call
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
     * @return \Widget\Call
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
     * @return \Widget\Call
     */
    public function complete(\Closure $fn)
    {
        $this->complete = $fn;
        return $this;
    }

    protected function createErrorException()
    {
        if ($error = error_get_last()) {
            return new \ErrorException($error['message'], $error['type'], 0, $error['file'], $error['line']);
        }
        return false;
    }
}