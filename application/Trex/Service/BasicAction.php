<?php
/**
 * BasicAction
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
 * @subpackage  Service
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-10-09 21:44:58
 */

class Trex_Service_BasicAction extends Trex_Service
{
    /**
     * Qwin_Url
     * @var Qwin_Url
     */
    public $url;

    /**
     * Qwin_Session
     * @var Qwin_Session
     */
    public $session;

    /**
     * Qwin_Request
     * @var Qwin_Request
     */
    public $request;

    /**
     * 元数据助手,负责元数据的获取,转换,检查等
     * @var Qwin_Trex_Metadata
     */
    public $metaHelper;

    /**
     * 根据应用结构配置,加载语言,元数据,模型等
     *
     * @param array $set 应用结构配置
     * @see Trex_Service_BasicAction $_set
     * @todo 是否需要对$config进行检查或转换
     */
    protected function process(array $config = null)
    {
        // 初始化常用的变量
        $this->request      = Qwin::run('Qwin_Request');
        $this->url          = Qwin::run('Qwin_Url');
        $this->session      = Qwin::run('Qwin_Session');
        $this->metaHelper   = Qwin::run('Qwin_Trex_Metadata');
        $ini                = Qwin::run('-ini');
        $set                = $this->_set = $config;
        $this->_config      = $ini->getConfig();
        $this->member      = $this->session->get('member');
        
        // 加载语言
        $languageResult = Qwin::run('Trex_Service_Language')->getLanguage($set);
        $languageName = $languageResult['data'];
        $languageClass = $set['namespace'] . '_' . $set['module'] . '_Language_' . $languageName;
        $this->_lang = Qwin::run($languageClass);
        if(null == $this->_lang)
        {
            $languageClass = 'Trex_Language' . $languageName;
            $this->_lang = Qwin::run($languageClass);
        }
        Qwin::addMap('-lang', $languageClass);

        $this->_meta = $this->metaHelper->getMetadataBySet($set);

        // 根据元数据定义的数据库,选择对应的连接类型
        if('padb' == $this->_meta['db']['type'])
        {
            Doctrine_Manager::getInstance()->setCurrentConnection('padb');
        }

        // 加载模型
        $modelName = $ini->getClassName('Model', $set);
        $this->_model = Qwin::run($modelName);
        if(null == $this->_model)
        {
            $modelName = 'Qwin_Trex_Model';
            $this->_model = Qwin::run($modelName);
        }
        Qwin::addMap('-model', $modelName);

        return $this;
    }

    public function loadView($class = null)
    {
        Qwin::load($class);
        if(null != $class && class_exists($class))
        {
            $this->_view['class'] = $class;
        }
        $view = Qwin::run($this->_view['class']);
        isset($this->_view['data']) && $view->setVarList($this->_view['data']);
        isset($this->_view['element']) && $view->setElementList($this->_view['element']);
        isset($this->_view['layout']) && $view->setLayout($this->_view['layout']);
        return $view;
    }

    /**
     * 显示验证错误的信息,当验证结果不为true时调用该方法
     *
     * @param Qwin_Validator_Result $result 验证结果
     * @param Qwin_Metadata $meta 元数据
     * @param boolen $dispaly 是否显示错误视图
     * @return string 错误信息
     */
    public function showValidateError(Qwin_Validator_Result $result, Qwin_Metadata $meta, $dispaly = true)
    {
        $message = $this->_lang->t('MSG_ERROR_FIELD')
                . $this->_lang->t($meta['field'][$result->field]['basic']['title'])
                . '<br />'
                . $this->_lang->t('MSG_ERROR_MSG')
                . $this->metaHelper->format($this->_lang->t($result->message), $result->param);
        if($dispaly)
        {
            $this->setRedirectView($message);
        }
        return $message;
    }
}
