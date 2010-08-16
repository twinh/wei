<?php
/**
 * TrexSetup
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
 * @since       2010-08-04 17:13:37
 */

class TrexSetup extends Qwin_Trex_Setup
{
    protected function _onControllerLoad(Qwin_Trex_Controller $controller)
    {
        /*$request = Qwin::run('Qwin_Request');
        $session = Qwin::run('Qwin_Session');
        $lang = null;

        /**
         * 获取语言
         *
        // 按优先级排列语言的数组
        $langList = array(
            $request->g('language'),
            $session->get('language'),
            $this->_config['interface']['language'],
        );
        foreach($langList as $val)
        {
            if(null != $val)
            {
                $lang = $val;
                break;
            }
        }
        $session->set('language', $lang);

        /**
         * 加载项目语言
         *
        $langFile = QWIN_ROOT_PATH . '/common/language/' . $lang . '.php';
        if(file_exists($langFile))
        {
            $controller->language = require_once $langFile;
        } else {
            $langFile = QWIN_ROOT_PATH . '/common/lang/' . $this->_config['interface']['language'] . '.php';
            $controller->lang = require_once $langFile;
        }

        // 加载当前模块语言
        $moduleLangFile = QWIN_ROOT_PATH . '/App/' . $set['namespace'] . '/' . $set['module'] . '/Lang/' . $lang . '.php';
        if(file_exists($module_lang_file))
        {
            $controller->lang += require_once $module_lang_file;
        } else {
            $module_lang_file = QWIN_ROOT_PATH . '/App/' . $set['namespace'] . '/' . $set['module'] . '/Lang/' . $config['i18n']['language'] . '.php';
            if(file_exists($module_lang_file))
            {
                $controller->lang += require_once $module_lang_file;
            }
        }

        echo '加载完毕!<p>';
        exit;*/
    }
}
