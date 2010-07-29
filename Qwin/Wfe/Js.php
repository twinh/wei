<?php
 /**
 * Jquery
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
 * @version   2009-12-12 16:44:00 utf-8 中文
 * @since     2009-12-12 16:44:00 utf-8 中文
 */

require_once 'Qwin/Wfe.php';

class Qwin_Wfe_Js extends Qwin_Wfe
{
    private $_js_code = array();
    private $_jq_code = array();
    private $_file = array();
    
    public function add($file)
    {
        if(file_exists($file) && !in_array($file, $this->_file))
        {
            $this->_file[$file] = $file;
        }
        return $this;
    }
    
    /**
     * 新建一个js代码组
     *
     * @param string $name js代码组名称
     */
    public function newJsCodeGroup($name)
    {
        if(isset($this->_js_code[$name]))
        {
            return false;
        }
        $this->_js_code[$name] = array();
        return $this;
    }
    
    /**
     * 向js代码组添加代码
     *
     * @param string $name js代码组名称
     * @param string $code js代码段
     */
    public function addJs($name, $code)
    {
        if($code)
        {
            $this->_js_code[$name][] = $code;
        }
        return $this;
    }
    
    /**
     * 打包一个组的js代码
     *
     * @param string $name js代码组名称
     */
    public function packJs($name)
    {
        $code = implode($this->line_break, $this->_js_code[$name]) . $this->line_break;
        return $this->_addJsTag($code);
    }
    
    /**
     * 删除一个js代码组
     *
     * @param string $name js代码组名称
     */
    public function unsetJs($name)
    {
        unset($this->_js_code[$name]);
        return $this;
    }
    
    /**
     * 给js代码加标签
     *
     * @param $data js代码窜
     */
    private function _addJsTag($data)
    {
        return '<script type="text/javascript">' . $this->line_break . $data . '</script>' . $this->line_break;
    }
    
    
    /**
     * 新建一个jquery的初始化代码组
     *
     * @param string $name jq代码组名称
     */
    public function newJqCodeGroup($name)
    {
        if(isset($this->_jq_code[$name]))
        {
            return false;
        }
        $this->_jq_code[$name] = array();
        return $this;
    }
    
    /**
     * 向jq代码组添加代码
     *
     * @param string $name jq代码组名称
     * @param string $code jq代码段
     */
    public function addJq($name, $code)
    {
        if($code)
        {
            $this->_jq_code[$name][] = $code . $this->line_break;
        }
        return $this;
    }
    
    /**
     * 打包一个组的jq代码
     *
     * @param string $name jq代码组名称
     */
    public function packJq($name)
    {
        $code = implode($this->line_break, $this->_jq_code[$name]) . $this->line_break;
        return $this->_addJqReadyCode($code);
    }
    
    /**
     * 删除一个jq代码组
     *
     * @param string $name jq代码组名称
     */
    public function unsetJq($name)
    {
        unset($this->_jq_code[$name]);
        return $this;
    }
    
    /**
     * 给jq代码加 ready
     *
     * @param $data js代码窜
     */
    private function _addJqReadyCode($data)
    {
        
        return 'jQuery(function($){' . $this->line_break . $data . '})' . $this->line_break;
    }
    
    /**
     * 将打包好的jq代码,加入到js代码组中
     *
     *
     */
    public function addJqToJs($jq_name, $js_name)
    {
        $code = $this->packJq($jq_name);
        return $this->addJs($jq_name, $code);
    }
    
    public function packAll()
    {
        $data = '';
        
        // 打包 jq 代码
        foreach($this->_jq_code as $val)
        {
            if(0 != count($val))
            {
                $data .= implode($this->line_break, $val) . $this->line_break;
            }
        }
        if('' != $data)
        {
            $data = $this->_addJqReadyCode($data);
        }
        
        // 打包 js 代码
        foreach($this->_js_code as $val)
        {
            if(0 != count($val))
            {
                $data .= implode($this->line_break, $val) . $this->line_break;
            }
        }
        if('' != $data)
        {
            $data = $this->_addJsTag($data);
        }
        
        // 先加载文件,再加载代码
        $data = $this->packFile() . $data;
        return $data;
    }
    
    public function packFile()
    {
        $data = '';
        // 打包 js 文件
        foreach($this->_file as $val)
        {
            $data .= '<script type="text/javascript" src="' . $val . '"></script>' . $this->line_break;
        }
        return $data;
    }
}
