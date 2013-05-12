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

    protected $cache = false;

    /**
     *
     * @var array
     */
    protected $data = array();

    protected $beforeSend;

    protected $complete;

    protected $time;

    protected $timeout;

    protected $type = 'url';

    protected $dataType = 'json';

    protected $headers = array();

    public function __invoke($options)
    {
        $this->setOption($options);

        switch ($this->type) {
            case 'url':
                $ch = curl_init();
                $opts = array();

                if ('POST' === strtoupper($this->method)) {
                    $opts[CURLOPT_URL] = $this->url;
                    $opts[CURLOPT_POST] = 1;
                    $opts[CURLOPT_POSTFIELDS] = $this->data;
                } else {
                    $query = http_build_query($this->data);
                    if (false === strpos('?', $this->url)) {
                        $opts[CURLOPT_URL] = $this->url . '?' . $query;
                    } else {
                        $opts[CURLOPT_URL] = $this->url . '?' . $query;
                    }
                }

                curl_setopt_array($opts);
                $response = curl_exec($ch);
                if (false === $response) {
                    $this->trigger('error', curl_error($ch));
                } else {
                    $this->handleResponse($response);
                }
                break;

            case 'soap':
                $soap = new \SoapClient($this->url);

                try {
                    $response = $soap->__soapCall($this->method, $this->data);
                    $this->handleResponse($response);
                } catch (\SoapFault $e) {
                    $this->trigger('error', $e);
                }
                break;
        }

        return $this;
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

    protected function handleResponse($response)
    {
        $data = $this->decode($data, $this->dataType);
        $this->trigger('success', $data);
    }

    protected function decode($data, $type)
    {
        switch ($type) {
            case 'json' :
                return json_decode($data);

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

    protected function trigger($name)
    {
        return $this->eventManager->trigger('call' . ucfirst($name));
    }
}