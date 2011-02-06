<?php
/**
 * List
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
 * @since       2010-10-09 21:20:47
 */

class Common_Service_List extends Common_Service_BasicAction
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
            'order' => array(),
            'where' => array(),
            'offset' => null,
            'limit' => null,
            'filter' => array(),
            'asAction' => 'list',
            'isView' => true,
            'filter' => true,
        ),
        'callback' => array(
            'datafilter' => null,
        ),
        'view' => array(
            'class' => 'Common_View_JqGridJson',
            'display' => true,
        ),
        'this' => null,
    );

    public function process(array $option = null)
    {
        // 初始配置
        $option = $this->_multiArrayMerge($this->_option, $option);
        $metaHelper = Qwin::call('Qwin_App_Metadata');
        if (null == $option['this']) {
            $option['this'] = Qwin::call($metaHelper->getClassName('Controller', $option['asc']));
        }

        // 通过父类,加载语言,元数据,模型等
        parent::process($option['asc']);

        // 初始化常用的变量
        $asc        = $this->config['asc'];
        $meta       = $this->_meta;
        $primaryKey = $meta['db']['primaryKey'];

        // 从模型获取数据
        $query = $metaHelper->getQueryByAsc($asc, array('db', 'view'));
        $metaHelper
            ->addSelectToQuery($meta, $query)
            ->addOrderToQuery($meta, $query, $option['data']['order'])
            ->addWhereToQuery($meta, $query, $option['data']['where'])
            ->addOffsetToQuery($meta, $query, $option['data']['offset'])
            ->addLimitToQuery($meta, $query, $option['data']['limit']);
        $dbData = $data = $query->execute()->toArray();
        $count = count($data);
        $totalRecord = $query->count();

        // 执行回调函数,转换数据
        if (isset($option['callback']['datafilter'])) {
            $option['callback']['datafilter'][1] = $data;
            $tempData = $this->executeCallback('datafilter', $option);
            null != $tempData && $data = $tempData;
        }

        // 对数据进行转换
        if ($option['data']['filter']) {
            $data = $metaHelper->filterArray($data, $option['data']['asAction'], $meta, $meta, array('view' => $option['data']['isView']));
        }

        // 获取布局
        $layout = $metaHelper->getListLayout($meta);
        if (null != $option['data']['list']) {
            $layout = array_intersect($layout, (array)$option['data']['list']);
        }

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
            $view = Qwin::call($option['view']['class']);
            $view->assign($result['view']['data']);
        }
        return $view;
    }
}
