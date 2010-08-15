<?php
/**
 * Resource
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
 * @since       2009-11-24 20:45:11
 * @todo        即时加载与加入wfe类数组中, 多版本文件支持,如 jQuery, 不同类型支持,如压缩.
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
        $this->_path = Qwin::run('-str')->toUrlSeparator(RESOURCE_PATH);
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
        Qwin::run('-js')->add($path);
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
        Qwin::run('-css')->add($path);
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
