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
 * @package     Widget
 * @author      Twin Huang <twinh@yahoo.cn>
 * @todo        add init data support
 * @todo        add array param support
 */
class Get extends ArrayWidget
{
    public $options = array(
        'parameters' => false,
    );

    public function __construct($options = null)
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
     * @param  string $name    the parameter name
     * @param  mixed  $default the default parameter value if the parameter does not exist
     * @return mixed  the parameter value
     */
    public function __invoke($name, $default = null)
    {
        return isset($this->data[$name]) ? $this->data[$name] : $default;
    }
}
