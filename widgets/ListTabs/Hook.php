<?php
/**
 * Hook
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
 * @since       2011-03-27 02:22:36
 */

class ListTabs_Hook extends Qwin_Hook_Abstract
{
    public function hookViewListTop($options)
    {
        $module = $options['view']['module'];
        $widget = Qwin::call('-widget');

        // 如果当前行为存在选项卡视图,加载该视图,否则直接输出默认选项卡内容
        $class = $module->getClass() . '_Widget_ListTabs';
        if(class_exists($class)) {
            return $widget->getByClass($class)->render($options['view']);
        } else {
            return $widget->get('ListTabs')->render($options['view']);
        }
    }
}
