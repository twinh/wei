<?php
/**
 * hook
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
 * @since       2011-02-11 14:20:57
 */

class ViewedRecords_Hook extends Qwin_Hook_Abstract
{
    protected $_option = array(
        'maxNum' => 8,
    );

    public function hookViewMainLeft($view)
    {
        return Qwin::widget('viewedrecords')->render($view);
    }

    public function hookViewRecord($option)
    {
        $meta = $option['meta'];
        $record = $option['record'];

        if (!empty($meta['page']['mainField'])) {
            $title = $record[$meta['page']['mainField']];
        } elseif (method_exists($meta, 'getMainFieldValue')) {
            $title = $meta->getMainFieldValue($record);
        } else {
            return false;
        }

        $session = Qwin::call('-session');
        $lang = Qwin::call('-lang');

        $viewRecords = (array)$session['viewedRecords'];
        $key = get_class($meta) . $record[$meta['db']['primaryKey']];

        // 最多保存8项 TODO 转换为微件,插件.
        if ($this->_option['maxNum'] <= count($viewRecords)) {
            array_pop($viewRecords);
        }

        // 加到第一项
        $viewRecords = array(
            $key => array(
            'title' => '[' . $lang[$meta['page']['title']] .  ']' . $title,
            'href' => $_SERVER['REQUEST_URI'],
            )
        ) + $viewRecords;

        $session['viewedRecords'] = $viewRecords;
        return true;
    }
}