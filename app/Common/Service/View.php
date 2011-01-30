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
 * @subpackage  Service
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-10-11 10:35:49
 */

class Common_Service_View extends Common_Service_BasicAction
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
            'asAction' => 'view',
            'isView' => true,
            'meta' => null,
        ),
        'callback' => array(
        ),
        'view' => array(
            'class' => 'Common_View_View',
            'display' => true,
        ),
        'this' => null,
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

    public function process(array $option = null)
    {
        // 初始配置
        $option = $this->_multiArrayMerge($this->_option, $option);
        if(isset($option['data']['meta']))
        {
            $this->_meta = $option['data']['meta'];
        }

        // 通过父类,加载语言,元数据,模型等
        parent::process($option['asc']);

        // 初始化常用的变量
        $metaHelper = $this->metaHelper;
        $meta = $this->_meta;
        $primaryKey = $meta['db']['primaryKey'];

        // TODO
        if('integer' == $meta['field'][$primaryKey]['db']['type']) {
            $option['data']['primaryKeyValue'] = (integer)$option['data']['primaryKeyValue'];
        }

        // 从模型获取数据
        $query = $metaHelper->getQueryByAsc($this->_asc, array('db', 'view'));
        $result = $query->where($primaryKey . ' = ?', $option['data']['primaryKeyValue'])->fetchOne();

        // 记录不存在,加载错误视图
        if(false == $result)
        {
            $result = array(
                'result' => false,
                'message' => $this->_lang->t('MSG_NO_RECORD'),
            );
            if($option['view']['display'])
            {
                $this->view->setRedirectView($result['message']);
            }
            return $result;
        }

        // TODO 插件?
        // 添加到最近查看项
        $metaHelper->setLastViewedItem($meta, $result);

        $data = $result->toArray();
        $data = $metaHelper->filterOne($data, $option['data']['asAction'], $meta, $meta, array('view' => $option['data']['isView']));

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
