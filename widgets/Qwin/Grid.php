<?php

/**
 * Qwin Framework
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
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 */

/**
 * Grid
 * 
 * @namespace   Qwin
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @author      Twin Huang <twinh@yahoo.cn>
 * @since       2011-01-18 15:24:53
 */
class Qwin_Grid extends Qwin_Widget
{
    /**
     * @var array $options          jqGrid的部分配置选项和微件配置
     * 
     *      -- url                  获取json数据的链接
     *
     *      -- datatype             数据类型
     *
     *      -- colNames             列的名称
     *
     *      -- colModel             列的模型
     *
     *      -- sortname             排序的域名称
     *
     *      -- sortorder            排序的域类型
     *
     *      -- rowNum               每列显示数目
     *
     *      -- rowList              每页显示数目的选项
     *
     *      -- caption              标题
     *
     *      -- rownumbers           是否显示记录为第几行
     *
     *      -- multiselect          是否允许多选
     *
     *      -- height               高度,默认为100%
     *
     *      -- width                宽度,默认为自动(auto)
     *
     *      -- autowidth            是否自动调整高度
     *
     *      -- pager                分页对象的字符串
     *
     *      -- viewrecords          是否在分页栏右下角显示记录数,默认为显示
     *
     *      -- forceFit             列宽度改变改变时,不改变表格宽度,从而不出现滚动条
     *
     *      -- emptyrecords         当记录为空时,右下角显示的提示语
     *
     *      -- prmNames             各参数的对应关系
     *
     *          -- page                 分页的查询名称
     *
     *          -- rows                 每页显示数目的查询名称
     *
     *          -- sort                 排序域查询的名称
     *
     *          -- order                排序类型的查询名称
     *
     *          -- search               搜索的查询名称
     */
    public $options = array(
        'id'            => null,
        'fields'        => array(),
        'layout'        => array(),
        'url'           => null,
        'datatype'      => 'json',
        'colNames'      => array(),
        'colModel'      => array(),
        'sortname'      => null,
        'sortorder'     => null,
        'rowNum'        => 10,
        'rowList'       => array('5', '10', '20', '30', '40', '50', '100'),
        'caption'       => false,
        'rownumbers'    => true,
        'multiselect'   => true,
        'height'        => '100%',
        'width'         => 'auto',
        'autowidth'     => false,
        'viewrecords'   => true,
        'forceFit'      => true,
        'emptyrecords'  => 'MSG_NO_RECORDS',
        'pager'         => null,//'#ui-jqgrid-page',
        'prmNames'      => array(
            'page'      => 'page',
            'rows'      => 'row',
            'sort'      => 'orderField',
            'order'     => 'orderType',
            'search'    => '_search',
            'nd'        => 'nd',
            'npage'     => 'npage'
        ),
    );

    /**
     * @var array $_jsonOptions      Json数据的配置选项
     *
     *      -- data                 原始数据,一般是从数据库取出,并经过转换
     *
     *      -- layout               布局数组,形式为 order => field
     *
     *      -- primaryKey           主键,主键隐藏 TODO!!!
     *
     *      -- options              jqGrid返回的Json数据的数组
     *
     *      -- page                 当前页面数
     *
     *      -- total                总页面数
     *
     *      -- records              总记录数
     *
     *      -- rows                 记录信息
     */
    protected $_jsonOptions = array(
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

    protected $_id = null;
    
    /**
     * 获取模块记录
     * 
     * @param string $name 记录名称
     * @param Qwin_Module $module 模块名称
     * @return Qwin_Record 
     */
    public function call($name = null, $module = null)
    {
        if (!$module) {
            $module = $this->module();
        }
        $class = $module->toClass() . '_' . ucfirst($name) . 'Grid';
        return $this->qwin($class);
    }
 
    /**
     * 渲染jqGrid界面
     *
     * @param array $options 配置选项
     */
    public function render(array $options = array())
    {
        $this->option(&$options);
        $options = $this->getGridData() + $options;

        // 设置编号
        if (!isset($options['id'])) {
            $options['id'] = 'ui-jqgrid-1';
            $this->_id = $options['id'];
        }
        
        // 设置Url
        if (!isset($options['url'])) {
            $options['url'] = $this->url->url($this->module(), 'list', array(
                'json' => true,
            ));
        }

        if (!$options['pager']) {
            $options['pager'] = '#' . $options['id'] . '-pager';
        }

        // 设置栏目
        if (empty($options['colNames'])) {
            $layout = $this->getLayout($options['layout']);
            foreach ($options['fields'] as $field) {
                if (isset($layout[$field['name']])) {
                    $options['colNames'][] = $this->lang[$field['name']];
                    $options['colModel'][] = $field;
                }
            }
        }

        // 获取jqGrid与其分页的id号
        $options['pager'] = substr($options['pager'], 1);

        $jqGridJson = json_encode($options);

        require $this->view->getFile(dirname(__FILE__) . '/Grid/default.php');
    }
    
    /**
     * 渲染Json数据
     *
     * @param array $options 配置选项
     */
    public function renderJson($options)
    {
        // 合并选项
        $options = (array)$options + $this->_jsonOptions;
        $this->options = $this->getGridData() + $this->options;
        $layout = $this->getLayout($options['layout']);
        
        $data = array();
        foreach ($options['data'] as $row) {
            $cell = array();

            foreach ($this->options['fields'] as $field) {
                if (isset($layout[$field['name']])) {
                    if (isset($row[$field['name']])) {
                        $cell[] = $row[$field['name']];
                    } else {
                        $cell[] = null;
                    }
                }
            }
            
            $data[] = array(
                $options['id'] => $row[$options['id']],
                'cell' => $cell,
            );
        }
        // 转换为jqGrid的行数据
        $options['options']['rows'] = $data;

        return json_encode($options['options']);
    }
    
    /**
     * 根据列表元数据定义和自定义列表栏目,获取列表布局
     * 
     * @param Qwin_Meta_List $list 列表元数据
     * @param string|array $layout 列表配置
     * @return $layout 列表布局数组 
     */
    public function getLayout($layout)
    {
        $layout = (string)$layout;
        if ($layout) {
            $layout = array_flip(explode(',', $layout));
        }

        foreach ($this->options['fields'] as $field) {
            if (!$layout || isset($layout[$field['name']])) {
                $data[$field['name']] = 1;
            }
        }
        return $data;
    }

    /**
     * 获取当前实例的编号
     *
     * @return string
     */
    public function getId()
    {
        return $this->_id;
    }
}
