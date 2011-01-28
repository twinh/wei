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
 * @package     Common
 * @subpackage  Service
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-10-11 17:14:08
 */

class Common_Service_Form extends Common_Service_BasicAction
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
            'primaryKeyValue' => null,
            'asAction' => 'add',
            'initalData' => array(),
        ),
        'callback' => array(
        ),
        'view' => array(
            'class' => 'Common_View_AddForm',
            'display' => true,
        ),
        'this' => null,
    );

    public function process(array $option = null)
    {
        // 初始配置
        $option = $this->_multiArrayMerge($this->_option, $option);

        // 通过父类,加载语言,元数据,模型等
        parent::process($option['asc']);

        // 初始化常用的变量
        $metaHelper = Qwin::run('Qwin_App_Metadata');
        $meta = $this->_meta;
        $primaryKey = $meta['db']['primaryKey'];
        $primaryKeyValue = $option['data']['primaryKeyValue'];

        $modelClass = $metaHelper->getClassName('Model', $this->_asc);
        $model = Qwin::run($modelClass);
        $query = $metaHelper->getQuery($meta, $model, 'db');

        /**
         * 空值 < 元数据表单初始值 < 根据主键取值 < 配置初始值(一般是从url中获取)
         */
        // 从元数据表单配置取值
        $formInitalData = $meta['field']->getFormValue();

        // 根据主键取值
        $copyRecordData = array();
        if (null != $primaryKeyValue) {
            $this->_result = $result = $query->where($primaryKey . ' = ?', $primaryKeyValue)->fetchOne();
            if (false !== $result) {
                // 删除null值
                foreach ($result as $name => $value) {
                    null !== $value && $copyRecordData[$name] = $value;
                }
            }
        }

        // 合并数据
        $data = array_merge($formInitalData, $copyRecordData, $option['data']['initalData']);

        // 处理数据
        $data = $metaHelper->convertOne($data, $option['data']['asAction'], $meta, $meta, array('view' => false));

        // 设置视图
        $result = array(
            'result' => true,
            'view' => array(
                'class' => $option['view']['class'],
                'data' => get_defined_vars(),
            ),
            'data' => $data,
        );
        // 加载视图
        if ($option['view']['display']) {
            $view = Qwin::run($option['view']['class']);
            $view->assign($result['view']['data']);
        }
        return $view;
    }
}
