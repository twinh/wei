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
 * @package     Trex
 * @subpackage  Service
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-10-09 21:20:47
 */

class Trex_Service_List extends Trex_Service_BasicAction
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
            'list' => array(),
            'order' => array(),
            'where' => array(),
            'offset' => null,
            'limit' => null,
            'filter' => array(),
            'convertAsAction' => 'list',
            'isLink' => true
        ),
        'trigger' => array(
            'dataConverter' => array(),
        ),
        'view' => array(
            'class' => 'Trex_View_JqGridJson',
            'display' => true,
        ),
    );

    public function process(array $config = null)
    {
        // 初始配置
        $config = $this->_multiArrayMerge($this->_config, $config);

        // 通过父类,加载语言,元数据,模型等
        parent::process($config['set']);

        // 初始化常用的变量
        $metaHelper = Qwin::run('Qwin_Trex_Metadata');
        $meta       = $this->_meta;
        $primaryKey = $meta['db']['primaryKey'];

        // 从模型获取数据
        $query = $meta->getDoctrineQuery($this->_set);
        $metaHelper
            ->addSelectToQuery($meta, $query)
            ->addOrderToQuery($meta, $query, $config['data']['order'])
            ->addWhereToQuery($meta, $query, $config['data']['where'])
            ->addOffsetToQuery($meta, $query, $config['data']['offset'])
            ->addLimitToQuery($meta, $query, $config['data']['limit']);
        $data = $query->execute()->toArray();
        $count = count($data);
        $totalRecord = $query->count();

        /**
         * 处理数据
         */
        $relatedField = $meta->connectMetadata($this->_meta);
        $relatedField->order();
        $data = $meta->convertDataToSingle($data);

        $config['trigger']['dataConverter'][1] = $data;
        $tempData = $this->executeTrigger('dataConverter', $config);
        null != $tempData && $data = $tempData;

        $listField = $meta->getListField($relatedField, $meta, $config['data']['list']);
        
        $data = $this->_meta->convertMultiData($listField, $relatedField, $config['data']['convertAsAction'], $data, $config['data']['isLink'], $meta['model']);

        /**
         * 设置视图
         */
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
