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
 * @version     $Id: Widget.php -1   $
 * @since       2010-10-09 21:20:47
 */

class JsonList_Widget extends Qwin_Widget_Abstract
{
    /**
     * 默认选项
     * @var array
     *
     *      -- module       模块标识
     *
     *      -- list         显示的域，可以是数组，或者用“,”分开
     *
     *      -- order        排列顺序, 0键为键名，1键为排序类型（DESC，ASC）
     *
     *      -- search       查询的语句
     *
     *      -- page         当前页数
     *
     *      -- row          每页显示数目
     *
     *      -- sanitise     是否转换数据
     *
     *      -- display      是否显示数据
     */
    protected $_defaults = array(
        'module'    => null,
        'listName'  => 'list',
        'list'      => null,
        'order'     => null,
        'search'    => null,
        'page'      => null,
        'row'       => null,
        'sanitise'  => true,
        'display'   => true,
    );

    
    public function execute(array $options = null)
    {
        // 初始化配置
        $options = array_merge($this->_options, $options);
        
        /* @var $meta Com_Meta */
        $meta = Com_Meta::getByModule($options['module']);

        // 检查元数据列表键名是否存在
        if (!$meta->offsetLoad($options['listName'], 'list')) {
            return $this->e('ERR_META_OFFSET_NOT_FOUND', $options['listName']);
        }

        // 处理每页显示数目‘
        $options['row'] = (int)$options['row'];
        $options['row'] <= 0 && $options['row'] = $meta['db']['limit'];
        
        // 处理页数
        $options['page'] = (int)$options['page'];
        $options['page'] <= 0 && $options['page'] = 1;

        // 从模型获取数据
        $query = Com_Meta::getQueryByModule($options['module'], array('type' => array('db', 'view')));
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

        // 显示的域
        $listFields = $meta[$options['listName']]->getBy('enabled', true);
        
        // 对数据进行转换
        if ($options['sanitise']) {
            foreach ($data as &$row) {
                $rowTemp = array_intersect_key($row, $listFields) + $listFields;
                $row = $meta->sanitise($rowTemp, 'list', array(
                    'view' => true,
                    'link' => true,
                    'notFilled' => true,
                ), $row);
            }
            unset($row, $rowTemp);
        }

        // 不展示视图，返回数据
        if (!$options['display']) {
            return array(
                'result' => true,
                'data' => get_defined_vars(),
            );
        }

        // 输出jqGrid Json视图
        $jqGrid = $this->_JqGrid;

        // 获取并合并布局
        if (!empty($options['list'])) {
            if (!is_array($options['list'])) {
                $options['list'] = Qwin_Util_String::split2d($options['list']);
            }
            $options['list'] = array_flip($options['list']);
            $listFields = array_intersect_key($listFields, $options['list']);
        }

        // 通过jqGrid微件获取数据
        $json = $jqGrid->renderJson(array(
            'data'          => $data,
            'layout'        => $listFields,
            'primaryKey'    => $meta['db']['id'],
            'options'       => array(
                'page'      => $options['page'],
                'total'     => ceil($total / $options['row']),
                'records'   => $total,
            ),
        ));

        // TODO 输出型视图
        echo $json;
        Qwin::call('-view')->setDisplayed();
    }
}
