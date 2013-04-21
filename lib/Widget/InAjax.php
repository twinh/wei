<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget;

/**
 * Check if the current request is an ajax(XMLHttpRequest) request
 *
 * @author      Twin Huang <twinhuang@qq.com>
 * @property    Request $request The request widget
 */
class InAjax extends AbstractWidget
{
    /**
     * Check if the current request is an ajax(XMLHttpRequest) request
     * 
     * @return bool
     */
    public function __invoke()
    {
        return $this->request->inAjax();
    }
}
