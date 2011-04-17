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

class Style_Widget extends Qwin_Widget_Abstract
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
        $config = Qwin::config();

        $session = Qwin::call('-session');
        // 按优先级排列语言的数组
        $styleList = array(
            Qwin::call('-request')->get('style'),
            $session['style'],
            $config['style'],
        );
        foreach ($styleList as $val) {
            if (null != $val) {
                $style = $val;
                break;
            }
        }

        if (!is_dir($this->_rootPath . 'source/' . $style)) {
            $style = $config['style'];
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
        return $this->_rootPath . 'source/' . $this->getName() . '/jquery.ui.theme.css';
    }
}