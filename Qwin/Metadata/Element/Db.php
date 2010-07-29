<?php
/**
 * Db
 *
 * Copyright (c) 2009-2010 Twin. All rights reserved.
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
 * @version   2010-7-27 18:13:16
 * @since     2010-7-27 18:13:16
 */

class Qwin_Metadata_Element_Db extends Qwin_Metadata_Element_Abstract
{
    /**
     * 获取样本数据
     *
     * @return array
     */
    public function getSampleData()
    {
        return array(
            'table' => NULL,
            'primaryKey' => 'id',
            'offset' => 0,
            'limit' => 1,
            'order' => array(),
            'where' => array(),
        );
    }

    /**
     * 获取数据表名称,不包含前缀
     *
     * @return string 数据表名称
     */
    public function getTable()
    {
        return $this->_data['table'];
    }

    /**
     * 获取整个数据表名称
     *
     * @return string 数据表名称
     */
    public function getFullTable()
    {
        if(isset($this->_data['_table']))
        {
            return $this->_data['_table'];
        }
        return $this->_getFullTable();
    }

    /**
     * 获取整个数据表名称
     *
     * @return string 数据表名称
     */
    public function _getFullTable()
    {
        $config = Qwin_Class::run('-ini');
        $this->_data['_table'] = $config['db']['prefix'] . $this->_data['table'];
        return $this->_data['_table'];
    }
}
