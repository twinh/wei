<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Widget;

use Widget\Stdlib\Parameter;

/**
 * A widget that handles the HTTP request parameters ($_POST)
 *
 * @author      Twin Huang <twinhuang@qq.com>
 * @property    Request $request The HTTP request widget
 */
class Post extends Parameter
{
    public function __construct(array $options = array())
    {
        parent::__construct($options);

        $this->data = &$this->request->getParameterReference('post');
    }
}
