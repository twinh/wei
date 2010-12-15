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
 * @package     Common
 * @subpackage  View
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-08-14 11:12:00
 */

class Common_View extends Qwin_Application_View
{
    public function __construct()
    {
        // 获取配置
        $this->_config = Qwin::run('-ini')->getConfig();

        $packerPath = QWIN_ROOT_PATH . '/cache/packer';

        // 设置css打包
        $cssPacker = Qwin::run('Qwin_Packer_Css');
        $cssPacker->setCachePath($packerPath)
            ->setCacheAge($this->_config['expiredTime'])
            ->setPathCacheAge($this->_config['expiredTime']);

        // 设置js打包
        $jsPacker = Qwin::run('Qwin_Packer_Js');
        $jsPacker->setCachePath($packerPath)
            ->setCacheAge($this->_config['expiredTime'])
            ->setPathCacheAge($this->_config['expiredTime']);

        $this->_data['cssPacker'] = $cssPacker;
        $this->_data['jsPacker'] = $jsPacker;

        $jquery = Qwin::run('Qwin_Resource_JQuery');
        $this->_data['jquery'] = $jquery;

        $jquery->setTheme($this->getStyle());
        
        $this->_theme = Qwin::run('-ini')->getConfig('interface.theme');
        $this->_layout = QWIN_RESOURCE_PATH . '/view/theme/' . $this->_theme . '/layout/common-control-panel.php';

        // 部分视图常用变量
        $this->_data['set'] = Qwin::run('-ini')->getSet();
        $this->_data['theme'] = $this->_theme;

        /**
         * 加载页眉导航的缓存
         */
        $this->adminMenu = Qwin::run('Qwin_Cache_List')->getCache('AdminMenu');

        $this->_data['lastViewedItem'] = Qwin::run('Qwin_Session')->get('lastViewedItem');
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

        $session = Qwin::run('Qwin_Session');
        // 按优先级排列语言的数组
        $styleList = array(
            Qwin::run('Qwin_Request')->g('style'),
            $session->get('style'),
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
        $session->set('style', $style);
        return $this->_style = $style;
    }

    public function display()
    {
        extract($this->_data, EXTR_OVERWRITE);
        require_once $this->_layout;
        return $this;
    }
}
