<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget;

/**
 * Post
 *
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class Post extends Parameter
{
    public function __construct(array $options = array())
    {
        parent::__construct($options);

        if (!isset($options['data'])) {
            $this->data = $_POST;
        }
    }
}
