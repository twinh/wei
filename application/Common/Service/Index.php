<?php
/**
 * Index
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
 * @since       2010-10-10 14:16:45
 */

class Common_Service_Index extends Common_Service_BasicAction
{
    /**
     * 服务的基本配置
     * @var array
     */
    protected $_option = array(
        'asc' => array(
            'namespace' => null,
            'module' => null,
            'controller' => null,
            'action' => null,
        ),
        'data' => array(
            'list' => array(),
            'isPopup' => false,
        ),
        'callback' => array(
            'beforeViewLoad' => array(),
        ),
        'view' => array(
            'class' => 'Common_View_JqGrid',
            'display' => true,
        ),
    );
    
    public function process(array $option = null)
    {
        // 合并配置
        $option = $this->_multiArrayMerge($this->_option, $option);

        // 通过父类,加载语言,元数据,模型等
        parent::process($option['asc']);

        // 初始化常用的变量
        $meta = $this->_meta;
        $primaryKey = $meta['db']['primaryKey'];
        $metaHelper = $this->metaHelper;

        $layout = $metaHelper->getListLayout($meta);
        if (null != $option['data']['list']) {
            $layout = array_intersect($layout, (array)$option['data']['list']);
        }

        // 是否以弹出框形式显示
        $isPopup = $option['data']['isPopup'];

        // 设置视图
        $view = array(
            'class' => $option['view']['class'],
            'data' => get_defined_vars(),
        );
        if ($option['view']['display']) {
            $viewObject = new $option['view']['class'];
            $viewObject->assign($view['data']);
        }
        
        return array(
            'result' => true,
            'view' => $view,
        );
    }
}
