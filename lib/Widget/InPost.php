<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2011 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget;

/**
 * Check if in post request
 *
 * @author      Twin Huang <twinhuang@qq.com>
 * @property    Request $request The request widget
 */
class InPost extends AbstractWidget
{
   /**
     * Check if the current request method is POST
     * 
     * @return bool
     */
    public function __invoke()
    {
        return $this->request->inPost();
    }
}
