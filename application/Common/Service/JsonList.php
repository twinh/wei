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

class Common_Service_JsonList extends Common_Service
{
    /**
     * 服务的基本配置
     * @var array
     */
    protected $_option = array(
        'asc'       => array(
            'package'     => null,
            'module'        => null,
            'controller'    => null,
            'action'        => null,
        ),
        'list'      => array(),
        'order'     => array(),
        'where'     => array(),
        'offset'    => null,
        'limit'     => null,
        'asAction'  => 'list',
        'isView'    => true,
        'sanitise'  => true,
        'display'   => true,
        'viewClass' => 'Common_View_JsonList',
    );

    public function process(array $option = null)
    {
        // 初始配置
        $option     = array_merge($this->_option, $option);
        $listField  = $option['list'];

        /* @var $meta Common_Metadata */
        $meta = Common_Metadata::getByAsc($option['asc']);

        // 从模型获取数据
        $query = $meta->getQuery(null, array('type' => array('db', 'view')));
        $meta
            ->addSelectToQuery($query)
            ->addOrderToQuery($query, $option['order'])
            ->addWhereToQuery( $query, $option['where'])
            ->addOffsetToQuery($query, $option['offset'])
            ->addLimitToQuery($query, $option['limit']);
        $dbData = $query->execute();
        $data   = $dbData->toArray();
        $count  = count($data);
        $total  = $query->count();

        // 对数据进行转换
        if ($option['sanitise']) {
            // TODO listField & listField2
            $listField2 = $this->_filterData($meta);
            foreach ($data as &$row) {
                $rowTemp = array_intersect_key($row, $listField2) + $listField2;
                $row = $meta->sanitise($rowTemp, $option['asAction'], array(
                    'view' => $option['isView'],
                    'link' => true,
                ), $row);
            }
        }

        // 设置返回结果
        $result = array(
            'result' => true,
            'data' => get_defined_vars(),
        );

        // 展示视图
        if ($option['display']) {
            $view = new $option['viewClass'];
            return $view->assign($result['data']);
        }

        return $result;
    }

    /**
     * 取出列表操作显示的数据
     *
     * @param Qwin_Metadata_Abstract $meta 元数据对象
     * @return array 数据
     */
    protected function _filterData($meta)
    {
        $result = array();
        foreach ($meta['field'] as $name => $field) {
            if (1 == $field['attr']['isList']) {
                $result[$name] = true;
            }
        }
        return $result;
    }
}
