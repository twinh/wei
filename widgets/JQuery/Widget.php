<?php
/**
 * jquery
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
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2011-01-29 16:42:43
 * @todo        按钮,遮罩
 */

class JQuery_Widget extends Qwin_Widget_Abstract
{
    public function render($options = null)
    {
        
    }

    /**
     * 加载核心文件
     *
     * @return string
     */
    public function loadCore()
    {
        $file = $this->_path . 'jquery.js';
        return $file;
    }

    /**
     * 加载UI文件
     *
     * @param string $name 名称
     * @return array
     */
    public function loadUi($name)
    {
        return array(
            'js' => $this->_path . 'ui/jquery.ui.' . $name . '.min.js',
            'css' => $this->_path . 'ui/jquery.ui.' . $name . '.css',
        );
    }

    /**
     * 加载效果
     *
     * @param string $name 名称
     * @return string
     */
    public function loadEffect($name)
    {
        return $this->_path . 'effects/jquery.effects.' . $name . '.min.js';
    }

    /**
     * 加载插件
     *
     * @param string $name 名称
     * @param string $type 类型,如min,pack
     * @return array
     */
    public function loadPlugin($name, $type = null)
    {
        $js = $this->_path . 'plugins/' . $name . '/jquery.' . $name;
        $js .= $type ? '.' . $type : null;
        $js .= '.js';
        return array(
            'js' => $js,
            'css' => $this->_path . 'plugins/' . $name . '/jquery.' . $name . '.css',
        );
    }
}
