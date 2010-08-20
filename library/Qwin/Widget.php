<?php
/**
 * Widget
 *
 * Copyright (c) 2008-2010 Twin Huang. All rights reserved.
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
 * @package     Qwin
 * @subpackage  Widget
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-08-20 15:26:25
 * @todo        形成一个规范的体系
 */

class Qwin_Widget
{
    public function __construct()
    {
        
    }

    function render($meta)
    {
        $retult = '';
        foreach($meta['_widget'] as $widgetSetting)
        {
            // 增加默认参数
            $widgetSetting[] = $meta;
            $retult .= Qwin::callByArray($widgetSetting);
        }
        return $retult;
    }
}
