<?php
/**
 * Controller
 *
 * AciionController is controller with some default action,such as index,list,
 * add,edit,delete,view and so on.
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
 * @package     Trex
 * @subpackage  Controller
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-7-28 15:19:18
 */

class Trex_Controller extends Qwin_Trex_Controller
{
    /**
     * 初始化各类和数据
     */
    public function __construct($option = null)
    {
        /**
         * 根据配置选择性加载语言,元数据,模型
         * @todo 对$option进行详细检查
         */
        if(null == $option)
        {
            $option = array(
                'language' => true,
                'metadata' => true,
                'model' => true,
            );
        } elseif(false == $option) {
            $option = array(
                'language' => false,
                'metadata' => false,
                'model' => false,
            );
        }

        $ini = Qwin::run('-ini');
        $this->_request = Qwin::run('Qwin_Request');
        $this->_url = Qwin::run('Qwin_Url');
        $set = $this->_set = $ini->getSet();
        $this->_config = $ini->getConfig();
        $this->_member = Qwin::run('Qwin_Session')->get('member');
       
        /**
         * 加载语言,同时将该命名空间下的通用模块语言类加入到当前模块的语言类下
         */
        if($option['language'])
        {
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
        }

        /**
         * 加载元数据
         */
        if($option['metadata'])
        {
            $metadataName = $ini->getClassName('Metadata', $set);
            if(class_exists($metadataName))
            {
                $this->_meta = Qwin_Metadata_Manager::get($metadataName);
            } else {
                $metadataName = 'Trex_Metadata';
                $this->_meta = Qwin::run($metadataName);
            }
            Qwin::addMap('-meta', $metadataName);
        }

        /**
         * 加载模型
         */
        if($option['model'])
        {
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
         * 访问控制
         */
        $this->_isAllowVisited();
    }

    /**
     * 是否有权限浏览该页面
     *
     * @return boolen
     */
    private function _isAllowVisited()
    {
        $ses  = Qwin::run('-ses');
        $member = $ses->get('member');

        // 未登陆则默认使用游客账号
        if(null == $member)
        {
            $set = array(
                'namespace' => 'Trex',
                'module' => 'Member',
                'controller' => 'Member',
            );
            $query =$this->_meta->getDoctrineQuery($set);
            $result = $query
                ->where('username = ?', 'guest')
                ->fetchOne();
            $member = $result->toArray();
            
            $ses->set('member',  $member);
            $ses->set('permisson', $member['group']['permission']);
            $ses->set('style', $member['theme']);
            $ses->set('language', $member['language']);
        }
        
        // 逐层权限判断
        $set = $this->_set;
        $permission = unserialize($member['group']['permission']);
        if(isset($permission[$set['namespace']]))
        {
            return true;
        }
        if(isset($permission[$set['namespace'] . '|' . $set['module']]))
        {
            return true;
        }
        if(isset($permission[$set['namespace'] . '|' . $set['module'] . '|' . $set['controller']]))
        {
            return true;
        }
        if(isset($permission[$set['namespace'] . '|' . $set['module'] . '|' . $set['controller'] . '|' . $set['action']]))
        {
            return true;
        }

        if('guest' == $member['username'])
        {
            $url = Qwin::run('-url');
            $url->to($url->createUrl(array(
                    'module' => 'Member',
                    'controller' => 'Log',
                    'action' => 'Login',
                )));
        } else {
            $this
                ->setRedirectView($this->_lang->t('MSG_PERMISSION_NOT_ENOUGH'))
                ->loadView()
                ->display();
            exit;
        }
        return false;
    }

    public function setRedirectView($message, $method = null)
    {
        $this->_view['class'] = 'Trex_Common_View_Redirect';
        $this->_view['data']['message'] = $message;
        $this->_view['data']['method'] = $method;
        return $this;
    }
}
