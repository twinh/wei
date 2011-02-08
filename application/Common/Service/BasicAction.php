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
 * @package     Common
 * @subpackage  Service
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-10-09 21:44:58
 */

class Common_Service_BasicAction extends Common_Service
{
    protected $_asc;

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
     * @var Qwin_Application_Metadata
     */
    public $metaHelper;

    /**
     * 根据应用结构配置,加载语言,元数据,模型等
     *
     * @param array $asc 应用结构配置
     * @see Common_Service_BasicAction $_set
     * @todo 是否需要对$config进行检查或转换
     */
    protected function process(array $option = null)
    {
        // 初始化常用的变量
        $this->request      = Qwin::call('-request');
        $this->url          = Qwin::call('-url');
        $this->session      = Qwin::call('-session');
        $this->metaHelper   = Qwin::call('Qwin_Application_Metadata');
        $this->config       = Qwin::call('-config');
        $this->_asc         = $asc = $option;
        $this->member       = $this->session->get('member');
        
        // 加载语言
        $langHelper = Qwin::call('Common_Helper_Language');
        $this->_lang = $langHelper->getObjectByAsc($this->_asc);
        Qwin::setMap('-lang', get_class($this->_lang));

        if (!isset($this->_meta)) {
            $this->_meta = $this->metaHelper->getMetadataByAsc($asc);
        }

        // 根据元数据定义的数据库,选择对应的连接类型
        if ('padb' == $this->_meta['db']['type']) {
            Doctrine_Manager::getInstance()->setCurrentConnection('padb');
        }

        // 加载模型
        $modelName = $this->metaHelper->getClassName('Model', $asc);
        $this->_model = Qwin::call($modelName);
        if(null == $this->_model)
        {
            $modelName = 'Qwin_Application_Model';
            $this->_model = Qwin::call($modelName);
        }
        Qwin::setMap('-model', $modelName);

        return $this;
    }
}
