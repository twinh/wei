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
 * @author      Twin Huang <twinh@yahoo.cn>
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
}
