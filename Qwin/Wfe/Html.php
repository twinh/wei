<?php
 /**
 * Html
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
 * @subpackage  Wfe
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2009-12-12 18:58:00
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
