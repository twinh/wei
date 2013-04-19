<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget;

/**
 * The server and execution environment parameters ($_SERVER) widget
 *
 * @author      Twin Huang <twinhuang@qq.com>
 * @property    \Widget\Request $request The HTTP request widget
 */
class Server extends Parameter
{
    /**
     * Constructor
     *
     * @param array $options
     */
    public function __construct(array $options = array())
    {
        parent::__construct($options);

        $this->data = &$this->request->getParameterReference('server');
    }
    
    /**
     * Returns the HTTP request headers
     * 
     * @return array
     */
    public function getHeaders()
    {
        $headers = array();
        foreach ($this->data as $name => $value) {
            if (0 === strpos($name, 'HTTP_')) {
                $headers[substr($name, 5)] = $value;
            }
        }
        return $headers;
    }
}
