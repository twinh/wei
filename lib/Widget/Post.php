<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget;

/**
 * The HTTP request parameters ($_POST) widget
 *
 * @author      Twin Huang <twinh@yahoo.cn>
 * @property    \Widget\Request $request The HTTP request widget
 */
class Post extends Parameter
{
    public function __construct(array $options = array())
    {
        parent::__construct($options);

        $this->data = &$this->request->getParameterReference('post');
    }
}
