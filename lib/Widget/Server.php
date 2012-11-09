<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2012 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget;

/**
 * Server
 *
 * @package     Widget
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class Server extends ArrayWidget
{
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
        } else {
            $this->data = $_SERVER;
        }
    }

    /**
     * Return server parameter
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
