<?php
/**
 * Qwin Framework
 *
 * @copyright   Copyright (c) 2008-2012 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Qwin;

/**
 * Request
 *
 * @package     Qwin
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
}
