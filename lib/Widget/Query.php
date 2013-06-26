<?php
/**
 * Widget Framework
 *
 * @copyright   Twin Huang Copyright (c) 2008-2013
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Widget;

use Widget\Stdlib\Parameter;

/**
 * A widget that handles the URL query parameters ($_GET)
 *
 * @author      Twin Huang <twinhuang@qq.com>
 * @property    Request $request The HTTP request widget
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
