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
 * @property \Widget\Server $server The server widget
 */
class Request extends Parameter
{
    /**
     * Constructor
     *
     * @param array $options
     */
    public function __construct(array $options = array())
    {
        parent::__construct($options);

        // Rebuild request parameters from other widgets
        if (!isset($options['data'])) {
            $order = ini_get('request_order') ?: ini_get('variables_order');

            $map = array(
                'G' => 'query',
                'P' => 'post',
                'C' => 'cookie'
            );

            foreach (str_split(strtoupper($order)) as $key) {
                if (isset($map[$key])) {
                    $this->data = $this->$map[$key]->toArray() + $this->data;
                }
            }
        }
    }

    /**
     * Returns the full url, which do not contain the fragment, for it never sent to the server
     *
     * @return string
     * @link http://snipplr.com/view.php?codeview&id=2734
     */
    public function getFullUrl()
    {
        $s = $this->server['HTTPS'] == 'on' ? 's' : '';
        $protocol = substr(strtolower($this->server['SERVER_PROTOCOL']), 0, strpos(strtolower($this->server['SERVER_PROTOCOL']), '/')) . $s;
        $port = ($this->server['SERVER_PORT'] == '80') ? '' : (':' . $this->server['SERVER_PORT']);

        return $protocol . '://' . $this->server['SERVER_NAME'] . $port . $this->server['REQUEST_URI'];
    }
    
    /**
     * Returns the client IP address
     *
     * @param  string $default The default ip address
     * @return string
     */
    public function getIp($default = '0.0.0.0')
    {
        return $this->server['HTTP_X_FORWARDED_FOR']
            ?: $this->server['HTTP_CLIENT_IP']
            ?: $this->server['REMOTE_ADDR']
            ?: $default;
    }
    
    /**
     * Check if the current request method is the specified string
     * 
     * @param string $method The method name to be compared
     * @return bool
     */
    public function inMethod($method)
    {
        return $method === strtoupper($this->server['REQUEST_METHOD']);
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
}
