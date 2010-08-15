<?php
 /**
 * Jquery
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
 * @since       2009-12-12 16:44:00
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
