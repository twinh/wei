<?php
/**
 * Widget
 *
 * Copyright (c) 2008-2011 Twin Huang. All rights reserved.
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
 * @since       2011-04-12 09:59:05
 */

class Style_Widget extends Qwin_Widget
{
    protected $_name = null;

    /**
     * 获取风格名称,风格为jQuery的主题
     *
     * @return string
     */
    public function getName()
    {
        if (isset($this->_name)) {
            return $this->_name;
        }

        $session = Qwin::call('-session');
        // 按优先级排列语言的数组
        $styleList = array(
            Qwin::call('-request')->get('style'),
            $session['style'],
            $this->_view->getOption('style'),
        );
        foreach ($styleList as $val) {
            if (null != $val) {
                $style = $val;
                break;
            }
        }

        if (!is_dir($this->_path . 'source/' . $style)) {
            $style = $this->_view->getOption('style');
        }
        $session['style'] = $style;
        return $this->_name = $style;
    }

    /**
     * 获取风格样式文件
     *
     * @return string
     */
    public function getCssFile()
    {
        return $this->_path . 'source/' . $this->getName() . '/jquery.ui.theme.css';
    }

    /**
     * 获取风格源文件目录
     *
     * @return string
     * @todo !!
     */
    public function getSourcePath()
    {
        return Qwin::config('resource') . 'widgets/Style/source/';
    }

     public function getStyles($path = null)
    {
        if (!$path) {
            $path = $this->getSourcePath();
        }
        $files = scandir($path);
        $styles = array();

        // 如果存在配置文件,表示有效风格
        foreach ($files as $file) {
            $styleFile = $path . $file . '/config.php';
            if (!is_file($styleFile)) {
                continue;
            }
            $styles[] = (require $styleFile) + array(
                'path' => $file
            );
        }

        // 重置风格路径
        $this->_stylePath = $path;
        return $styles;
    }
    
    public function getResource()
    {
        $files = scandir($this->_path . 'source/');
        $resources = array();
        foreach ($files as $file) {
            if (is_file($this->_path . 'source/' . $file . '/config.php')) {
                $resources[$file] = $file;
            }
        }
        return $resources;
    }
}
