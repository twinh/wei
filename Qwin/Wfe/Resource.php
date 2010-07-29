<?php
/**
 * RS 的名称
 *
 * RS 的简要介绍
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
 * @version   2009-11-20 16:39:01 utf-8 中文
 * @since     2009-11-24 20:45:11 utf-8 中文
 * @todo      即时加载与加入wfe类数组中, 多版本文件支持,如 jQuery, 不同类型支持,如压缩.
 */

require_once 'Qwin/Wfe.php';

class Qwin_Wfe_Resource extends Qwin_Wfe
{
    private $_is_loaded = array();
    private $_path;
    private $_php_path;
    
    function __construct()
    {
        // resource path
        $this->_path = Qwin_Class::run('-str')->toUrlSeparator(RESOURCE_PATH);
    }
    /*
    // 加载jquery插件
    qw('-rsc')->load('jquery/plugin/layout');
    => RESOURCE_PATH . /js/jquery/plugin/layout/jquery.layout.js
    //如果 false 不自动加载css文件
    && RESOURCE_PATH . /js/jquery/plugin/layout/jquery.layout.css
    
    qw('-rsc')->load('js/qwin/url');
    => RESOURCE_PATH . /js/qwin/url.js
    
    qw('-rsc')->load('css/admin');
    => RESOURCE_PATH . /css/admin.css
    
    qw('-rsc')->load('js/ckeditor/');
    => RESOURCE_PATH . /js/ckeditor/ckeditor.js
    
    虽然没有该文件
    qw('-rsc')->load('js/ckeditor');
    => RESOURCE_PATH . /js/ckeditor.js
    */
    public function load($path)
    {
        $path = explode('/', strtolower($path));
        $ext = $path[0];
        switch($path[0])
        {
            case 'jquery' :
                return $this->_loadJquery($path);
                break;
            case 'js' :
                return $this->_loadJs($path);
                break;
            case 'css' :
                return $this->_loadCss($path);
                break;
        }
        return $this;
    }
    
    private function _loadJs($path)
    {
        $path_end = &$path[count($path) - 1];
        if('' == $path_end)
        {
            $path_end = $path[count($path) - 2];
        }
        $path_end .= '.js';
        array_unshift($path, $this->_path);
        $path = implode('/', $path);
        Qwin_Class::run('-js')->add($path);
        return $this;
    }
    
    
    /**
     * 添加css文件
     *
     */
    private function _loadCss($path)
    {
        $path_end = &$path[count($path) - 1];
        $path_end .= '.css';
        array_unshift($path, $this->_path);
        $path = implode('/', $path);
        Qwin_Class::run('-css')->add($path);
        return $this;
    }
    
    /**
     * 加载 jQuery 的各类文件
     * @param array $path 经过load解析的$path
     * @reutrn string
     * @todo 版本, 自定义命名规则
     */
    private function _loadJquery($path)
    {
        array_unshift($path, $this->_path, 'js');
        $path_end = &$path[count($path) - 1];
        $type = $path[3];
        switch($type)
        {
            case 'effects' :
                $file = $type . '.' . $path_end . '.js';
                array_pop($path);
                array_push($path, $file);
                qw('-js')->add(implode('/', $path));
                break;
            case 'ui' :
                array_push($path, 'ui.' . $path_end);
                $file_part = implode('/', $path);
                qw('-css')->add($file_part . '.css');
                qw('-js')->add($file_part . '.js');
                break;
            case 'plugin' :
                array_push($path, 'jquery.' . $path_end);
                $file_part = implode('/', $path);
                qw('-css')->add($file_part . '.css');
                qw('-js')->add($file_part . '.js');
                break;
            case 'theme' :
                array_push($path, 'jquery-ui.css');
                $file_part = implode('/', $path);
                qw('-css')->add($file_part);
                break;
        }
        return $this;
    }
}
