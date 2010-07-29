<?php
/**
 * Abstract
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
 * @version   2010-7-26 14:14:35
 * @since     2010-7-26 14:14:35
 */

abstract class Qwin_Metadata_Element_Abstract extends Qwin_Metadata_AccessArray
{
    protected $_data = array();

    public function getSampleData()
    {
        return NULL;
    }
    
    /**
     * 设置数据
     *
     * @param array 任意数据
     * @preturn object 当前对象
     */
    public function setData($data)
    {
        $this->_data = $data;
        return $this;
    }

    public function addData($data)
    {
        $this->_data += $data;
        return $this;
    }

    /**
     *
     * @param <type> $metadata
     * @return <type>
     */
    protected function _format($metadata)
    {
        return $metadata + $this->getSampleData();
    }
    
    /**
     * 将数据作为一个整体进行格式化,例如,为数据赋予NULL值等
     */
    public function format()
    {
        $this->_data = $this->_format($this->_data);
        return $this;
    }

    
    /**
     * 将数据作为一个以为数组进行格式化,用于field,model等键名
     */
    protected  function _formatAsArray()
    {
        foreach($this->_data as $key => $row)
        {
            $this->_data[$key] = $this->_format($row);
        }
        return $this;
    }
}
