<?php

/**
 * Widget Framework
 *
 * @copyright   Twin Huang Copyright (c) 2008-2013
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget;

/**
 * Get
 *
 * @package     Widget
 * @author      Twin Huang <twinh@yahoo.cn>
 * @property \Widget\Router $router The router widget
 */
class Get extends Parameter
{
    /**
     * Constructor
     *
     * @param array $options
     */
    public function __construct(array $options = array())
    {
        parent::__construct($options);

        // FIXME router start or not
        if (!isset($options['data'])) {
            $this->data = $this->router->matchRequestUri() ?: $_GET;
        }
    }
}
