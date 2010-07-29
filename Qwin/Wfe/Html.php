<?php
 /**
 * Html
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
 * @version   2009-12-12 18:58:00 utf-8 中文
 * @since     2009-12-12 18:58:00 utf-8 中文
 */

require_once 'Qwin/Wfe.php';

class Qwin_Wfe_Html extends Qwin_Wfe
{
    private $_code = array();
    /**
     * 新增一个html代码组
     *
     * @param string 代码组名称
     */
    function newGroup($name)
    {
        if(isset($this->_code[$name]))
        {
            return false;
        }
        $this->_code[$name] = array();
        return $this;
    }
    
    /**
     * 向代码组添加代码
     *
     * @param string $name 代码组名称
     * @param string $code 代码段
     */
    public function add($name, $code)
    {
        if($code)
        {
            $this->_code[$name][] = $code;
        }
        return $this;
    }
    
    /**
     * 打包一个组的代码
     *
     * @param string $name 代码组名称
     */
    public function pack($name)
    {
        $code = implode($this->line_break, $this->_code[$name]) . $this->line_break;
        return $this->_addJsTag($code);
    }
    
    /**
     * 删除一个js代码组
     *
     * @param string $name js代码组名称
     */
    public function unsetGroup($name)
    {
        unset($this->_code[$name]);
        return $this;
    }
    
    public function packAll()
    {
        $data = '';
        foreach($this->_code as $val)
        {
            $data .= implode($this->line_break, $val) . $this->line_break;
        }
        return $data;
    }
}
