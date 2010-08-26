<?php
/**
 * Abstract
 *
 * Copyright (c) 2009-2010 Twin. All rights reserved.
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
 * @version   2010-7-28 15:19:18
 * @since     2010-7-28 15:19:18
 */

class Trex_Controller extends Qwin_Trex_Controller
{
    /**
     * 初始化各类和数据
     */
    public function __construct()
    {
        $ini = Qwin::run('-ini');
        $this->_request = Qwin::run('Qwin_Request');
        $this->_url = Qwin::run('Qwin_Url');
        $set = $this->_set = $ini->getSet();
        $this->_config = $ini->getConfig();
       
        /**
         * 访问控制
         */
        $this->_isAllowVisited();

        /**
         * 加载语言,同时将该命名空间下的通用模块语言类加入到当前模块的语言类下
         */
        $languageName = $this->getLanguage();
        $commonLanguageName = $set['namespace'] . '_Common_Language_' . $languageName;
        $languageName = $set['namespace'] . '_' . $set['module'] . '_Language_' . $languageName;
        $this->_lang = Qwin::run($languageName);
        if(null == $this->_lang)
        {
            $languageName = 'Trex_Language';
            $this->_lang = Qwin::run($languageName);
        }
        $this->_commonLang = Qwin::run($commonLanguageName);
        if(null != $this->_commonLang)
        {
            $this->_lang->merge($this->_commonLang);
        }
        Qwin::addMap('-lang', $languageName);

        /**
         * 加载元数据
         */
        $metadataName = $ini->getClassName('Metadata', $set);
        $this->_meta = Qwin_Metadata_Manager::get($metadataName);
        if(null == $this->_meta)
        {
            $metadataName = 'Trex_Metadata';
            $this->_meta = Qwin::run($metadataName);
        }
        Qwin::addMap('-meta', $metadataName);

        /**
         * 加载模型
         */
        $modelName = $ini->getClassName('Model', $set);
        $this->_model = Qwin::run($modelName);
        if(null == $this->_model)
        {
            $modelName = 'Qwin_Trex_Model';
            $this->_model = Qwin::run($modelName);
        }
        Qwin::addMap('-model', $modelName);
    }

    /**
     * 是否有权限浏览该页面
     *
     * @return boolen
     */
    private function _isAllowVisited()
    {
        return true;
    }

    public function setRedirectView($message, $method = null)
    {
        $this->_view['class'] = 'Trex_Common_View_Redirect';
        $this->_view['data']['message'] = $message;
        $this->_view['data']['method'] = $method;
    }
}
