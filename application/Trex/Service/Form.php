<?php
/**
 * 用于添加页面的表单
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
 * @since       2010-10-11 17:14:08
 */

class Trex_Service_Form extends Trex_Service_BasicAction
{
    /**
     * 服务的基本配置
     * @var array
     */
    protected $_config = array(
        'set' => array(
            'namespace' => null,
            'module' => null,
            'controller' => null,
            'action' => null,
        ),
        'data' => array(
            'primaryKeyValue' => null,
            'asAction' => 'add',
        ),
        'callback' => array(
        ),
        'view' => array(
            'class' => 'Trex_View_AddForm',
            'display' => true,
        ),
        'this' => null,
    );

    public function process(array $config = null)
    {
        // 初始配置
        $config = $this->_multiArrayMerge($this->_config, $config);

        // 通过父类,加载语言,元数据,模型等
        parent::process($config['set']);

        // 初始化常用的变量
        $metaHelper = Qwin::run('Qwin_Trex_Metadata');
        $meta = $this->_meta;
        $primaryKey = $meta['db']['primaryKey'];
        $primaryKeyValue = $config['data']['primaryKeyValue'];

        $modelClass = $metaHelper->getClassName('Model', $this->_set);
        $model = Qwin::run($modelClass);
        $query = $metaHelper->getQuery($meta, $model, array('type' => array('db')));
        
        /**
         * 三种模式　
         * 1.复制,根据主键从模型获取初始值
         * 2.从url获取值
         * 3. 获取模型默认值
         * TODO 可配置化
         */
        if(null != $primaryKeyValue)
        {
            $this->_result = $result = $query->where($primaryKey . ' = ?', $primaryKeyValue)->fetchOne();
            // 记录不存在,加载错误视图
            if(false == $result)
            {
                $return = array(
                    'result' => false,
                    'message' => $this->_lang->t('MSG_NO_RECORD'),
                );
                if($config['view']['display'])
                {
                    $this->setRedirectView($return['message']);
                }
                return $return;
            }
            $data = $result->toArray();
        } else {
            // 从配置元数据中取出表单初始值,再从url地址参数取出初始值,覆盖原值
            $data = $meta['field']->getSecondLevelValue(array('form', '_value'));
            $data = $metaHelper->getUrlData($data);
        }
        unset($data[$primaryKey]);

        // 处理数据
        $data = $metaHelper->convertOne($data, $config['data']['asAction'], $meta, $meta, array('view' => false));

        // 设置视图
        $this->_view = array(
            'class' => $config['view']['class'],
            'data' => get_defined_vars(),
        );

        if($config['view']['display'])
        {
            $this->loadView()->display();
        }
        return array(
            'result' => true,
            'view' => $this->_view,
            'data' => $data,
        );
    }
}
