<?php
/**
 * Qwin Framework
 *
 * Copyright (c) 2008-2011 Twin Huang. All rights reserved.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 */

namespace Qwin;

/**
 * ToText
 *
 * @package     Qwin
 * @subpackage  Widget
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @author      Twin Huang <twinh@yahoo.cn>
 * @since       2012-01-26 16:39:15
 */
class ToText extends Widget
{
    /**
     * Convert widget source to text
     */
    public function __invoke($data)
    {
        // 其他 '=', "\0", "%00", "\r", '\0', '%00', '\r' ?
        // 过滤不可见字符
        return preg_replace(
            array('/[\\x00-\\x08\\x0B\\x0C\\x0E-\\x1F]/', '/&(?!(#[0-9]+|[a-z]+);)/is'),
            array('', '&amp;'),
            // 替换html标签,制表符,换行符
            str_replace(
                array('&', '%3C', '<', '%3E', '>', '"', "'", "\t", ' '),
                array('&amp;', '&lt;', '&lt;', '&gt;', '&gt;', '&quot;', '&#39;', '&nsbp;&nsbp;&nsbp;&nsbp;', '&nbsp;'),
                $data
            )
        );
    }
}
