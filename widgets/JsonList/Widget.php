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
        'list'      => null,
        'layout'    => array(),
        'order'     => null,
        'search'    => null,
        'page'      => null,
        'row'       => null,
        'sanitise'  => true,
        'display'   => true,
    );

    
    public function render($options = null)
    {
        // 初始配置
        $options    = (array)$options + $this->_options;
        
        /* @var $listMeta Qwin_Meta_List */
        $list = $options['list'];

        // 检查列表元数据是否合法
        if (!is_object($list) || !$list instanceof Qwin_Meta_List) {
            $this->e('ERR_META_ILLEGAL');
        }
        $meta = $list->getParent();
        
        // 处理每页显示数目‘
        $options['row'] = (int)$options['row'];
        $options['row'] <= 0 && $options['row'] = $list['db']['limit'];
        
        // 处理页数
        $options['page'] = (int)$options['page'];
        $options['page'] <= 0 && $options['page'] = 1;

        // 从模型获取数据
        $query = $meta->getQuery(null, array('type' => array('db', 'view')));
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
            $rowLayout = array_combine(array_values($list['layout']), array_pad(array(), count($list['layout']), null));
            foreach ($data as &$row) {
                $rowTemp = array_intersect_key($row, $rowLayout) + $rowLayout;
                $row = $list->sanitise($rowTemp, array(
                    'view' => true,
                    'link' => true,
                    'sanitise' => 'list',
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
        $layout = $list['layout'];
        if (!empty($options['layout'])) {
            if (!is_array($options['layout'])) {
                $options['layout'] = Qwin_Util_String::split2d($options['layout']);
            }
            $layout = array_intersect($layout, $options['layout']);
        }

        // 通过jqGrid微件获取数据
        $json = $jqGrid->renderJson(array(
            'data'          => $data,
            'layout'        => $layout,
            'id'            => $meta['db']['id'],
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
