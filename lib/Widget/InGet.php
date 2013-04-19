<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2011 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget;

/**
 * Check if the current request method is GET 
 *
 * @author      Twin Huang <twinhuang@qq.com>
 * @property    \Widget\Request $request The request widget
 */
class InGet extends AbstractWidget
{
    /**
     * Check if the current request method is GET
     * 
     * @return bool
     */
    public function __invoke()
    {
        return $this->request->inGet();
    }
}
