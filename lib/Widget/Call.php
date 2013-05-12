<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget;

/**
 * Call
 *
 * @author      Twin Huang <twinh@yahoo.cn>
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

    protected $time;

    protected $timeout;

    protected $type = 'url';

    protected $dataType = 'json';

    protected $headers = array();

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

    public function __invoke($options)
    {
        $this->setOption($options);

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
            CURLOPT_RETURNTRANSFER => true
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
                    $opts[CURLOPT_URL] = $this->url . '?' . $query;
                }
            }
        }

        curl_setopt_array($ch, $opts);
        $this->trigger('beforeSend', $ch);
        $response = curl_exec($ch);
        if (false === $response) {
            $this->trigger('error', array($this, '', curl_error($ch)));
        } else {
            $this->handleResponse($response, $ch);
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
            $this->trigger('beforeSend', $soap);
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
            case 'raw' :
                return $data;

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

            default:
                // serializer->decode
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
}