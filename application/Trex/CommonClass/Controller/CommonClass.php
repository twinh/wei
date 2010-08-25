<?php
 /**
 * 通用的二级分类
 *
 * 通用二级分类的后台控制器
 *
 * Copyright (c) 2009 Twin. All rights reserved.
 *
 * LICENSE:
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author    Twin Huang <twinh@yahoo.cn>
 * @copyright Twin Huang
 * @license   http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @version   2009-11-21 12:18 utf-8 中文
 * @since     2009-11-21 12:18 utf-8 中文
 */

class Trex_CommonClass_Controller_CommonClass extends Trex_Controller
{
    /**
     * on 函数
     */
    public function onAfterDb($action, $data)
    {
        Qwin::run('Project_Helper_CommonClass')->write($data);
    }

    /**
     * db 转换函数
     * @todo 转义还原, map的安全检查,结构应该是二维 stdClass
     */
    public function convertDbValue($val, $name, $row, $row_copy)
    {
        $val = str_replace('\"', '"', $val);
        $data = Qwin::run('-arr')->jsonDecode($val, 'pear');
        return serialize($data);
    }

    public function convertListOperation($value, $name, $data, $copyData)
    {
        $primaryKey = $this->_meta['db']['primaryKey'];
        $url = $this->_url->createUrl($this->_set, array('action' => 'Add', '_data[sign]' => $data['sign']));
        $html = Qwin_Helper_Html::jQueryButton($url, $this->_lang->t('LBL_ACTION_ADD_NEXT'), 'ui-icon-plusthick')
              . parent::convertListOperation($value, $name, $data, $copyData);
        return $html;
    }
}
