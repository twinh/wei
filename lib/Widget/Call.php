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
    protected $async = false;
    
    protected $method = 'get';
    
    protected $cache = false;
    
    protected $data = array();
    
    protected $beforeSend;
    
    protected $complete;
    
    protected $time;
    
    protected $timeout;
    
    protected $type = 'json';
    
    protected $headers = array();
    
    public function __invoke($props)
    {
        $call = new static($props);
        
        // file_get_content
        // curl
        // soap
        // telnet
        return $call;
    }
    
    public function get($url, $props = array())
    {
        
    }
    
    public function post($url, $props = array())
    {
        
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
}