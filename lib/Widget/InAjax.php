<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget;

/**
 * Check if in ajax request
 *
 * @package     Widget
 * @author      Twin Huang <twinh@yahoo.cn>
 * @property \Widget\Server $server The server widget
 */
class InAjax extends WidgetProvider
{
    public function __invoke()
    {
        return 'xmlhttprequest' == strtolower($this->server['HTTP_X_REQUESTED_WITH']);
    }
}
