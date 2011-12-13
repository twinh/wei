<?php
/**
 * Index
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
 * @subpackage  ListAction
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-10-10 14:16:45
 */

class Qwin_ListAction extends Qwin_Widget
{
    /**
     * @var array           默认选项
     * 
     *      -- get          用户请求的参数,默认为$_GET数组
     * 
     *      -- layout       布局
     * 
     *      -- row          每页显示数目
     * 
     *      -- display      是否显示视图
     */
    public $options = array(
        'jqGrid'    => null,
        'order'     => array(),
        'row'       => 10,
        'layout'    => array(),
        'get'       => null,
        'display'   => true,
    );
    
    public $jqGridOptions = array(
        'id'            => null,
        'layout'        => array(),
        'url'           => null,
        'datatype'      => 'json',
        'colNames'      => array(),
        'colModel'      => array(),
        'sortname'      => null,
        'sortorder'     => null,
        'rowNum'        => 15,
        'rowList'       => array('15', '30', '50', '100', '200', '500'),
        'caption'       => false,
        'rownumbers'    => true,
        'multiselect'   => true,
        'height'        => '100%',
        'width'         => '100%',
        'autowidth'     => true,
        'viewrecords'   => true,
        'forceFit'      => true,
        'emptyrecords'  => 'MSG_NO_RECORDS',
        'pager'         => null,//'#ui-jqgrid-page',
    );

    /**
     * 显示列表数据
     *
     * @param array $options 选项
     * @return mixed
     */
    public function call(array $options = array())
    {
        $this->option(&$options);
        $jqGrid = $options['jqGrid'] + $this->jqGridOptions;

        // 显示哪些域
        //$jqGrid['layout'] = $options['layout'];
        
        // 用户请求参数
        $get = $options['get'] ? $options['get'] : $_GET;
        if (!$jqGrid['url']) {
            $jqGrid['url'] = $this->url->build(array('json' => true) + $get);
        }

        // 不显示视图，直接返回数据
        if (!$options['display']) {
            return array(
                'result' => true,
                'data' => get_defined_vars(),
            );
        }

        // 设置编号
        if (!isset($jqGrid['id'])) {
            $jqGrid['id'] = 'ui-jqgrid-1';
            $this->_id = $jqGrid['id'];
        }
        
        // 设置Url
        if (!isset($jqGrid['url'])) {
            $jqGrid['url'] = $this->url->url($this->module(), 'list', array(
                'json' => true,
            ));
        }
        
        $lang = $this->lang;
        if (empty($jqGrid['colNames'])) {
            foreach ($jqGrid['colModel'] as $column) {
                $jqGrid['colNames'][] = $lang->field($column['name']);
            }
        }

        // 获取jqGrid与其分页的id号
        $jqGrid['pager'] = $jqGrid['id'] . '-pager';

        // 加载列表视图
        $this->view->assign(get_defined_vars());
    }
}