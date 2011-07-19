<?php
/**
 * Menu
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
 * @package     Com
 * @subpackage  AdminMenu
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @since       2010-5-25 08:10:46
 */

class Member_Menu_DbRecord extends Record_Widget
{
    public function postSave($event)
    {
        $data = Query_Widget::getByModule('member/menu')
            ->select('id, category_id, title, target, url')
            ->orderBy('order ASC')
            ->fetchArray();
        /**
         * 菜单数据，键名表示菜单所在级别
         */
        $menus = array(
            0 => array(),
            1 => array(),
        );
        foreach ($data as $key => $row) {
            if (null == $row['category_id']) {
                $menus[0][$row['id']] = $row;
            }
            foreach ($data as $subRow) {
                if ($row['id'] == $subRow['category_id']) {
                    $menus[1][$row['id']][] = $subRow;
                }
            }
        }
        Qwin_Util_File::writeArray(Qwin::config('root') . 'cache/menu.php', $menus);
    }
    
    public function postDelete($event)
    {
        return $this->postSave($event);
    }
}
