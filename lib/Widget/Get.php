<?php

/**
 * Widget Framework
 *
 * @copyright   Twin Huang Copyright (c) 2008-2012
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget;

/**
 * Get
 *
 * @package     Request
 * @author      Twin Huang <twinh@yahoo.cn>
 * @todo        add get array
 */
class Get extends ArrayWidget
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
            // todo router start or not
            $this->data = $this->router->matchRequestUri() ?: $_GET;
        }
    }

    /**
     * Return get parameter
     *
     * @param  string $name    The parameter name
     * @param  mixed  $default The default parameter value if the parameter does not exist
     * @return mixed  The parameter value
     */
    public function __invoke($name, $default = null)
    {
        return isset($this->data[$name]) ? $this->data[$name] : $default;
    }
    
    /**
     * Get a integer parameter
     * 
     * @param string $name The parameter name
     * @param int $min The min value for the parameter
     * @param int $max The max value for the parameter
     * @return int
     */
    public function int($name, $min = null, $max = null)
    {
        $value = intval($this->data[$name]);

        if (!is_null($min) && $value < $min) {
            return $min;
        } elseif (!is_null($max) && $value > $max) {
            return $max;
        }

        return $value;
    }
}
