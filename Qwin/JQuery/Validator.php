<?php
 /**
 * jqgrid
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
 * @version   2010-02-01 13:30 utf-8 中文
 * @since     2010-02-01 13:30 utf-8 中文
 */
class Qwin_JQuery_Validator
{
    /**
     * 获取验证的 js 数据
     *
     * @param array $set 配置数组
     * @return json 验证规则的 json 数据
     */
    public function getRule($set)
    {
        $validator_rule = array();
        foreach($set as $field)
        {
            if(isset($field['validation']['rule']))
            {
                $validator_rule[$field['form']['name']] = $field['validation']['rule'];
            }
        }
        return Qwin_Class::run('-arr')->jsonEncode($validator_rule);
    }
}
