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
 * @package     Trex
 * @subpackage  Service
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-10-11 10:35:49
 */

class Trex_Service_View extends Trex_Service_BasicAction
{
    /**
     * 该服务的基本配置
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
            'convertAsAction' => 'view',
            'isLink' => true
        ),
        'trigger' => array(
        ),
        'view' => array(
            'isLoad' => true,
            'class' => 'Trex_View_JqGridJson',
        ),
    );

    /**
     * 处理结果的配置
     * @var array
     */
    protected $_result = array(
        'result' => true,
        'message' => null,
        'step' => null,
        'view' => null,
        'data' => null,
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

        // 从模型获取数据
        $query = $meta->getDoctrineQuery($this->_set);
        $result = $query->where($primaryKey . ' = ?', $config['data']['primaryKeyValue'])->fetchOne();

        // 记录不存在,加载错误视图
        if(false == $result)
        {
            $result = array(
                'result' => false,
                'message' => $this->_lang->t('MSG_NO_RECORD'),
            );
            if($config['view']['isLoad'])
            {
                $this->setRedirectView($result['message'])
                    ->loadView()
                    ->display();
            }
            return $result;
        }

        // 处理数据
        $relatedField = $meta->connectMetadata($this->_meta);
        $relatedField->order();

        // 根据行为,获取对应的分组列表
        $methodName = 'get' . $config['data']['convertAsAction'] . 'GroupList';
        if(method_exists($relatedField, $methodName))
        {
            $groupList = call_user_func(array($relatedField, $methodName));
        } else {
            $groupList = $relatedField->getGroupList();
        }
        

        $data = $result->toArray();
        $data = $meta->convertDataToSingle($data);
        $data = $meta->convertSingleData($relatedField, $relatedField, $config['data']['convertAsAction'], $data, $config['data']['isLink'], $meta['model']);

        // 设置视图
        $this->_view = array(
            'class' => $config['view']['class'],
            'data' => get_defined_vars(),
        );

        if($config['view']['isLoad'])
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
