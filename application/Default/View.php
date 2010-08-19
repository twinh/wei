<?php
/**
 * View
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
 * @package     Default
 * @subpackage  View
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-08-14 11:12:00
 */

class Default_View extends Qwin_Trex_View
{
    public function __construct()
    {
        Qwin::addMap('-jquery', 'Qwin_Resource_JQuery');
        $this->_jquery = Qwin::run('-jquery');
        $this->_jquery->setTheme($this->getStyle());
        
        $this->_theme = Qwin::run('-ini')->getConfig('interface.theme');
        $this->_layout = QWIN_RESOURCE_PATH . '/view/theme/' . $this->_theme . '/layout/common-control-panel.php';
    }

    /**
     * 获取风格,风格为jQuery的主题
     *
     * @return string
     */
    public function getStyle()
    {
        if(isset($this->_style))
        {
            return $this->_style;
        }

        // 按优先级排列语言的数组
        $styleList = array(
            Qwin::run('Qwin_Request')->g('style'),
            Qwin::run('Qwin_Session')->get('style'),
            Qwin::run('-ini')->getConfig('interface.style'),
        );
        foreach($styleList as $val)
        {
            if(null != $val)
            {
                $style = $val;
                break;
            }
        }

        if(!file_exists(QWIN_RESOURCE_PATH . '/js/jquery/themes/' . $style))
        {
            $style = Qwin::run('-ini')->getConfig('interface.style');
        }
        return $this->_style = $style;
    }

    public function display()
    {
        require_once $this->_layout;
    }
}
