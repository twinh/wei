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
 * @package     Com
 * @subpackage  Service
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-10-09 21:20:47
 */

class Com_Widget_JsonList extends Qwin_Widget_Abstract
{
    /**
     * 服务的基本配置
     * @var array
     */
    protected $_defaults = array(
        'module'    => null,
        'list'      => null,
        'order'     => null,
        'search'    => null,
        'page'      => null,
        'row'       => null,
        'asAction'  => 'list',
        'isView'    => true,
        'sanitise'  => true,
        'display'   => true,
        'viewClass' => 'Com_View_JsonList',
    );

    public function execute(array $options = null)
    {
        // 初始配置
        $options     = array_merge($this->_options, $options);

        // 处理显示域
        $listFields  = $options['list'];
        if (is_string($listFields)) {
            $listFields = Qwin_Util_String::split2d($listFields);
        }
        
        /* @var $meta Com_Metadata */
        $meta = Com_Metadata::getByModule($options['module']);

        // 处理每页显示数目‘
        $options['row'] = (int)$options['row'];
        $options['row'] <= 0 && $options['row'] = $meta['db']['limit'];

        // 从模型获取数据
        $query = Com_Metadata::getQueryByModule($options['module'], array('type' => array('db', 'view')));
        $meta
            ->addSelectToQuery($query)
            ->addOrderToQuery($query, $options['order'])
            ->addWhereToQuery($query, $options['search'])
            ->addOffsetToQuery($query, ($options['page'] - 1) * $options['row'])
            ->addLimitToQuery($query, $options['row']);
        $dbData = $query->execute();
        $data   = $dbData->toArray();
        $count  = count($data);
        $total  = $query->count();

        // 对数据进行转换
        if ($options['sanitise']) {
            // TODO listField & listField2
            $listField2 = $this->_filterData($meta);
            foreach ($data as &$row) {
                $rowTemp = array_intersect_key($row, $listField2) + $listField2;
                $row = $meta->sanitise($rowTemp, $options['asAction'], array(
                    'view' => $options['isView'],
                    'link' => true,
                    'notFilled' => true,
                ), $row);
            }
        }

        // 设置返回结果
        $result = array(
            'result' => true,
            'data' => get_defined_vars(),
        );

        // 展示视图
        if ($options['display']) {
            $view = new $options['viewClass'];
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
