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

        if (!isset($options['data'])) {
            $this->data = $_SERVER;
        }
    }
}
