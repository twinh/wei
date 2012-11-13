<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2012 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget;

/**
 * Request
 *
 * @package     Widget
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class Request extends ArrayWidget
{
    /**
     * Options
     *
     * @var array
     */
    public $options = array(
        'parameters' => false,
    );

    /**
     * Constructor
     *
     * @param array $options
     */
    public function __construct(array $options = array())
    {
        parent::__construct($options);

        if (is_array($this->options['parameters'])) {
            $this->data = $this->options['parameters'];
        // rebuild request parameters from other widgets
        } else {
            $order = ini_get('request_order') ?: ini_get('variables_order');

            $map = array('G' => 'get', 'P' => 'post', 'C' => 'cookie');

            foreach (str_split(strtoupper($order)) as $key) {
                if (isset($map[$key])) {
                    $this->data = $this->$map[$key]->toArray() + $this->data;
                }
            }
        }
    }

    /**
     * Return request parameter
     *
     * @param  string $name    the parameter name
     * @param  mixed  $default the default parameter value if the parameter does not exist
     * @return mixed  the parameter value
     */
    public function __invoke($name, $default = null)
    {
        return isset($this->data[$name]) ? $this->data[$name] : $default;
    }
    
    /**
     * Get full url
     * 
     * @return string
     * @see http://snipplr.com/view.php?codeview&id=2734
     */
    public function fullUrl()
    {
        $s = $this->server['HTTPS'] == 'on' ? 's' : '';
        $protocol = substr(strtolower($this->server['SERVER_PROTOCOL']), 0, strpos(strtolower($this->server['SERVER_PROTOCOL']), '/')) . $s;
        $port = ($this->server['SERVER_PORT'] == '80') ? '' : (':' . $this->server['SERVER_PORT']);

        return $protocol . '://' . $this->server('SERVER_NAME') . $port . $this->server['REQUEST_URI'];
    }
}
