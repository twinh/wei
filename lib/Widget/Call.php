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
    protected $method = 'get';

    protected $cache;

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

    protected $time;

    protected $timeout;

    protected $type = 'url';

    protected $dataType = 'json';

    protected $wsdl = true;

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
        $options && $this->setOption($options);

        switch ($this->type) {
            case 'url':
                $this->handleUrl();
                break;

            case 'soap':
                $this->handleSoap();
                break;
        }

        return $this;
    }

    public function handleUrl()
    {
        $ch = curl_init();
        $opts = array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => true
        );

        if ('POST' === strtoupper($this->method)) {
            $opts[CURLOPT_URL] = $this->url;
            $opts[CURLOPT_POST] = 1;
            $opts[CURLOPT_POSTFIELDS] = $this->data;
        } else {
            if ($this->data) {
                $query = http_build_query($this->data);
                if (false === strpos('?', $this->url)) {
                    $opts[CURLOPT_URL] = $this->url . '?' . $query;
                } else {
                    $opts[CURLOPT_URL] = $this->url . '&' . $query;
                }
            } else {
                $opts[CURLOPT_URL] = $this->url;
            }
        }

        if ($this->timeout >= 0) {
            $opts[CURLOPT_TIMEOUT] = $this->timeout;
        }

        // Set HTTP headers
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

        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $isSuccess = $statusCode >= 200 && $statusCode < 300 || $statusCode === 304;

        if (false !== $response) {
            list($this->responseHeader, $this->responseText) = explode("\r\n\r\n", $response, 2);
        }

        if ($isSuccess) {
            $this->handleResponse($this->responseText, $ch);
        } else {
            $this->trigger('error', array($this, '', curl_error($ch)));
        }
        curl_close($ch);
        $this->trigger('complete', array($this));
    }

    public function handleSoap()
    {
        if ($this->wsdl) {
            $soap = new \SoapClient($this->url);
        } else {
            $soap = new \SoapClient(null, array(
                'location' => $this->url,
                'uri' => $this->url,
                'trace' => 1
            ));
        }

        try {
            $this->trigger('beforeSend', array($this, $soap));
            $response = $soap->__soapCall($this->method, $this->data);
            $this->handleResponse($response, $soap);
        } catch (\SoapFault $e) {
            $this->trigger('error', array($this, 'exception', $e));
        }
        $this->trigger('complete', array($this));
    }

    public function get($url, $data, $callback)
    {
        return $this->processMethod($url, $data, $callback, 'GET');
    }

    public function post($url, $data, $callback)
    {
        return $this->processMethod($url, $data, $callback, 'POST');
    }

    protected function processMethod($url, $data, $callback, $method)
    {
        if (is_callable($data)) {
            $callback = $data;
            $method = $callback;
            $data = array();
        }

        return $this(array(
            'url' => $url,
            'method' => $method,
            'data' => $data,
            'success' => $callback
        ));
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
                // todo

            case 'text':
                return $data;

            case 'query' :
                $output = array();
                parse_str($data, $output);
                return $output;

            case 'serialize' :
                return unserialize($data);

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
        //return $this->eventManager->trigger('call' . ucfirst($name));
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
}