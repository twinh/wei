<?php
/**
 * Widget Framework
 *
 * @copyright   Twin Huang Copyright (c) 2008-2013
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget;

/**
 * The URL query parameters ($_GET) widget
 *
 * @author      Twin Huang <twinh@yahoo.cn>
 * @property    \Widget\Request $request The HTTP request widget
 */
class Query extends Parameter
{
    /**
     * Constructor
     *
     * @param array $options
     */
    public function __construct(array $options = array())
    {
        parent::__construct($options);

        $this->data = &$this->request->getParameterReference('get');
    }
}
