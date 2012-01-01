<?php
/**
 * Qwin Framework
 *
 * Copyright (c) 2008-2012 Twin Huang. All rights reserved.
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
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 */

/**
 * JQuery
 * 
 * @package     Qwin
 * @subpackage  Application
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @author      Twin Huang <twinh@yahoo.cn>
 * @since       2011-01-29 16:42:43
 */
class Qwin_JQuery extends Qwin_Widget
{
    public $options = array(
        'dir' => null,
    );
    
    public function __construct($source = null)
    {
        parent::__construct($source);
        $this->_dir = dirname(dirname(dirname(__FILE__))) . '/apps/views/jquery';
    }
    
    /**
     * 获取jQuery文件目录
     * 
     * @return string
     */
    public function getDir()
    {
        return $this->_dir;
    }
    
    public function call()
    {
        return $this;
    }

    /**
     * 获取核心文件
     *
     * @return string
     */
    public function getCore()
    {
        $file = $this->_dir . '/jquery.js';
        return $file;
    }
    
    /**
     * 获取jQuery插件/UI等的文件路径
     * 
     * @param string $name 插件/UI等名称,多个以逗号隔开
     * @return array 文件数组
     */
    public function get($name)
    {
        $names = explode(',', $name);
        $files = array();
        
        foreach ($names as $name) {
            $name = trim($name);
            $files[] = $this->_dir . '/' . $name . '/jquery.' . $name . '.js';
            $files[] = $this->_dir . '/' . $name . '/jquery.' . $name . '.css';
        }
        return $files;
    }
    
    /**
     * 加载jQuery插件/UI等的文件路径
     * 
     * @param string $name 插件/UI等名称,多个以逗号隔开
     * @return string html代码
     * @todo 如果是磁盘路径,应该转换为Url
     */
    public function load($name)
    {
        $names = explode(',', $name);
        $html = '';
        
        foreach ($names as $name) {
            $name = trim($name);
            
            $file = $this->_dir . '/' . $name . '/jquery.' . $name . '.js';
            if (is_file($file)) {
                $html .= '<script type="text/javascript" src="' . $file . '"></script>' . "\n";
            }
            
            $file = $this->_dir . '/' . $name . '/jquery.' . $name . '.css';
            if (is_file($file)) {
                $html .= '<link rel="stylesheet" type="text/css" media="all" href="' . $file . '" />' . "\n";
            }
        }
        return $html;
    }
    
    /**
     * 获取主题样式
     * 
     * @param string $name 主题名称
     * @return string
     */
    public function getTheme($name)
    {
        return $this->_dir . '/themes/' . $name . '/jquery.ui.theme.css';
    }
}
