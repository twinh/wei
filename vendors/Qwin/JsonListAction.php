<?php
/**
 * List
 *
 * Copyright (c) 2008-2011 Twin Huang. All rights reserved.
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
 * @package     Widget
 * @subpackage  JsonListAction
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id: Widget.php -1   $
 * @since       2010-10-09 21:20:47
 */

class Qwin_JsonListAction extends Qwin_Widget
{
    /**
     * 默认选项
     * @var array
     *
     *      -- list         列表元数据
     *
     *      -- layout       显示的域，可以是数组，或者用“,”分开
     *
     *      -- order        排列顺序, 0键为键名，1键为排序类型（DESC，ASC）
     *
     *      -- search       查询的语句
     *
     *      -- page         当前页数
     *
     *      -- row          每页显示数目
     *
     *      -- sanitise     转换选项,false表示不转换
     *
     *      -- display      是否显示数据
     */
    public $options = array(
        'meta'      => null,
        'list'      => 'list',
        'db'        => 'db',
        'layout'    => array(),
        'order'     => null,
        'search'    => null,
        'page'      => null,
        'row'       => null,
        'sanitise'  => array(
            'nullTxt'   => true,
            'emptyTxt'  => true,
            'sanitiser' => true,
            'sanitise'  => true,
            'action'    => 'list',
            'relation'  => true,
        ),
        'display'   => true,
    );

    
    public function render($options = null)
    {
        // 初始配置
        $options = (array)$options + $this->_options;
        
        // 检查元数据是否合法
        /* @var $meta Com_Meta */
        $meta = $options['meta'];
        if (!Qwin_Meta::isValid($meta)) {
            throw new Qwin_Exception('ERR_META_ILLEGAL');
        }

        // 检查列表元数据是否合法
        if (!($list = $meta->offsetLoad($options['list'], 'list'))) {
            throw new Qwin_Exception('ERR_LIST_META_NOT_FOUND');
        }
        
        // 检查数据库元数据是否合法
        if (!($db = $meta->offsetLoad($options['db'], 'db'))) {
            throw new Qwin_Exception('ERR_DB_META_NOT_FOUND');
        }
        
        // 处理每页显示数目
        $options['row'] = (int)$options['row'];
        $options['row'] <= 0 && $options['row'] = $list['db']['limit'];
        $options['row'] > 500 && $options['row'] = 500;
        
        // 处理页数
        $options['page'] = (int)$options['page'];
        $options['page'] <= 0 && $options['page'] = 1;
        
        // 从模型获取数据
        $query = Query_Widget::getByMeta($db)
            ->leftJoinByType(array('db', 'view'))
            //->addRawSelect()
            ->addRawWhere($options['search'])
            ->addRawOrder($options['order'])
            ->offset(($options['page'] - 1) * $options['row'])
            ->limit($options['row']);
        $dbData = $query->execute();
        $data   = $dbData->toArray();
        $count  = count($data);
        $total  = $query->count();

        // 对数据进行转换
        if ($options['sanitise']) {
            $rowLayout = array_combine(array_values($list['layout']), array_pad(array(), count($list['layout']), null));
            foreach ($data as &$row) {
                $rowTemp = array_intersect_key($row, $rowLayout) + $rowLayout;
                $row = $this->sanitiser($list, $rowTemp, $options['sanitise'], $row);
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

        // 通过jqGrid微件获取数据
        $json = $this->jqGrid->renderJson(array(
            'list'          => $list,
            'data'          => $data,
            'layout'        => $options['layout'],
            'id'            => $meta['db']['id'],
            'options'       => array(
                'page'      => $options['page'],
                'total'     => ceil($total / $options['row']),
                'records'   => $total,
            ),
        ));

        return $this->view->displayJson($json);
    }
}
