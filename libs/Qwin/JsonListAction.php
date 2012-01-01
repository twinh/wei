<?php
/**
 * Qwin Framework
 *
 * Copyright (c) 2008-2012 Twin Huang. All rights reserved.
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
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 */

/**
 * JsonListAction
 * 
 * @package     Qwin
 * @subpackage  Application
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @author      Twin Huang <twinh@yahoo.cn>
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
    
    protected $jsonOptions = array(
        'list'          => null,
        'data'          => array(),
        'layout'        => array(),
        'primaryKey'    => array(),
        'options'       => array(
            'page'          => 1,
            'total'         => 1,
            'records'       => 0,
            'rows'          => array(),
        ),
    );

    public function call(array $options = array())
    {
        $this->option(&$options);
        $jqGrid = $options['jqGrid'];
        $reocrd = $options['record'];

        // 处理每页显示数目
        $options['row'] = intval((string)$options['row']);
        $options['row'] <= 0 && $options['row'] = $reocrd->options['limit'];
        $options['row'] > 500 && $options['row'] = 500;
        
        // 处理页数
        $options['page'] = intval((string)$options['page']);
        $options['page'] <= 0 && $options['page'] = 1;

        // 从模型获取数据
        $query = $this->query()
            ->leftJoinByType(array('db', 'view'))
            //->addRawSelect()
            ->addRawWhere((string)$options['search'])
            ->addRawOrder($options['order'])
            ->offset(($options['page'] - 1) * $options['row'])
            ->limit($options['row']);
        $dbData = $query->execute();
        $data   = $dbData->toArray();
        $count  = count($data);
        $total  = $query->count();

        // 对数据进行转换
        /*if ($options['sanitise']) {
            $rowLayout = array_combine(array_values($list['layout']), array_pad(array(), count($list['layout']), null));
            foreach ($data as &$row) {
                $rowTemp = array_intersect_key($row, $rowLayout) + $rowLayout;
                $row = $this->sanitiser($list, $rowTemp, $options['sanitise'], $row);
            }
            unset($row, $rowTemp);
        }*/

        // 不展示视图，返回数据
        if (!$options['display']) {
            return array(
                'result' => true,
                'data' => get_defined_vars(),
            );
        }
        
        // 合并选项
        $jqGridJson = $this->jsonOptions;
        $json = array();
        foreach ($data as $row) {
            $cell = array();
            foreach ($jqGrid['colModel'] as $column) {
                if (isset($row[$column['index']])) {
                    $cell[] = $row[$column['index']];
                } else {
                    $cell[] = null;
                }
            }
            $json[] = array(
                'id' => $row['id'],
                //$options['id'] => $row[$options['id']],
                'cell' => $cell,
            );
        }

        $result = array(
            'page'      => $options['page'],
            'total'     => ceil($total / $options['row']),
            'records'   => $total,
            'rows'      => $json,
        );
        return $this->view->displayJson($result);
    }
}
