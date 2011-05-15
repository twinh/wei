<?php
/**
 * JqGrid
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
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       v0.7.0 2011-01-18 15:24:53
 */

class JqGrid_Widget extends Qwin_Widget_Abstract
{
    /**
     * @var array $_options          界面的配置选项
     *
     *      -- options               jqGrid的部分配置选项
     *
     *          -- url                  获取json数据的链接
     *
     *          -- datatype             数据类型
     *
     *          -- colNames             列的名称
     *
     *          -- colModel             列的模型
     *
     *          -- sortname             排序的域名称
     *
     *          -- sortorder            排序的域类型
     *
     *          -- rowNum               每列显示数目
     *
     *          -- rowList              每页显示数目的选项
     *
     *          -- caption              标题
     *
     *          -- rownumbers           是否显示记录为第几行
     *
     *          -- multiselect          是否允许多选
     *
     *          -- height               高度,默认为100%
     *
     *          -- width                宽度,默认为自动(auto)
     *
     *          -- autowidth            是否自动调整高度
     *
     *          -- pager                分页对象的字符串
     *
     *          -- viewrecords          是否在分页栏右下角显示记录数,默认为显示
     *
     *          -- forceFit             列宽度改变改变时,不改变表格宽度,从而不出现滚动条
     *
     *          -- emptyrecords         当记录为空时,右下角显示的提示语
     *
     *          -- prmNames             各参数的对应关系
     *
     *              -- page                 分页的查询名称
     *
     *              -- rows                 每页显示数目的查询名称
     *
     *              -- sort                 排序域查询的名称
     *
     *              -- order                排序类型的查询名称
     *
     *              -- search               搜索的查询名称
     */
    protected $_defaults = array(
        'id'            => null,//'ui-jqgrid-table',
        'meta'          => null,
        'list'          => null,
        'layout'        => array(),
        'options'        => array(
            'url'           => '',
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
            'autowidth'     => true,
            'viewrecords'   => true,
            'forceFit'      => true,
            'toolbar'       => false,
            //'toolbar'       => array(true, 'top'),
            //'ondblClickRow' => null,
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
     *      -- options               jqGrid返回的Json数据的数组
     *
     *          -- page                 当前页面数
     *
     *          -- total                总页面数
     *
     *          -- records              总记录数
     *
     *          -- rows                 记录信息
     */
    protected $_jsonOptions = array(
        'data'          => array(),
        'layout'        => array(),
        'primaryKey'    => array(),
        'options'        => array(
            'page'          => 1,
            'total'         => 1,
            'records'       => 0,
            'rows'          => array(),
        ),
    );

    protected $_id = null;
 
    /**
     * 渲染jqGrid界面
     *
     * @param array $options 配置选项
     */
    public function render($options)
    {
        // 合并选项
        $options = $options + $this->_options;
        $jqGrid = $options['options'] + $this->_options['options'];

        // 检查元数据是否合法
        $meta = $options['meta'];
        if (false === Qwin_Meta::isValid($meta)) {
            $this->e('ERR_META_NOT_DEFINED');
        }
        
        // 检查元数据中是否包含表单定义
        if (!$meta->offsetLoad($options['name'], 'list')) {
            return $this->e('ERR_META_OFFSET_NOT_FOUND', $options['name']);
        }
        $listMeta = $meta[$options['name']];
        
        // 设置编号
        if (!$options['id']) {
            $options['id'] = 'ui-jqgrid-' . $meta->getId();
            $this->_id = $options['id'];
        }

        if (!$jqGrid['pager']) {
            $jqGrid['pager'] = '#' . $options['id'] . '-pager';
        }

        // 设置栏目
        if (empty($jqGrid['colNames'])) {
            // 获取并合并布局
            $layout = $options['list']->getBy('enabled', true);
            if ($options['layout']) {
                !is_array($options['layout']) && $options['layout'] = explode(',', $options['layout']);
                $options['layout'] = array_flip($options['layout']);
                $layout = array_intersect_key($layout, (array)$options['layout']);
            }
            
            // 根据布局获取栏数据
            $col = $this->getColByListLayout($layout, $meta, $options['lang']);
            $jqGrid['colNames'] = $col['colNames'];
            $jqGrid['colModel'] = $col['colModel'];
        }

        // 设置排序 配置自定义 > 元数据定义 > 主键
        if (!$jqGrid['sortname']) {
            if (isset($meta['db']['order'][0]) && is_array($meta['db']['order'][0])) {
                $jqGrid['sortname']  = $meta['db']['order'][0][0];
                $jqGrid['sortorder'] = $meta['db']['order'][0][1];
            } else {
                $jqGrid['sortname']  = $meta['db']['id'];
                $jqGrid['sortorder'] = 'DESC';
            }
        }

        // 设置每页数目
        $jqGrid['rowNum'] = intval($jqGrid['rowNum']);
        if (-1 > $jqGrid['rowNum']) {
            $jqGrid['rowNum'] = $meta['db']['limit'];
        }

        // 通过Minify加载Css和Js
        $this->_widget
            ->get('Minify')
            ->add($this->_rootPath . '/source/jquery.jqgrid.css')
            ->add($this->_rootPath . '/source/i18n/grid.locale-en.js')
            ->add($this->_rootPath . '/source/jquery.jqgrid.js');

        // 翻译语言
        $jqGrid['emptyrecords'] = $options['lang'][$jqGrid['emptyrecords']];

        // 获取jqGrid与其分页的id号
        $options['pager'] = substr($jqGrid['pager'], 1);

        $jqGridJson = Qwin_Util_Array::jsonEncode($jqGrid);

        require $this->_rootPath . 'view/default.php';
    }

    /**
     * 渲染Json数据
     *
     * @param array $options 配置选项
     */
    public function renderJson($options)
    {
        // 合并选项
        $options = $this->merge($this->_jsonOptions, $options);
        
        $data = array();
        foreach ($options['data'] as $row) {
            $cell = array();
            foreach ($options['layout'] as $field => $bool) {
                $cell[] = $row[$field];
            }
            $data[] = array(
                $options['primaryKey'] => $row[$options['primaryKey']],
                'cell' => $cell,
            );
        }
        // 转换为jqGrid的行数据
        $options['options']['rows'] = $data;

        return json_encode($options['options']);
    }

    /**
     * 根据列表布局数组,获取栏目配置
     *
     * @param array $layout 列表布局数组
     * @param Qwin_Meta $meta 元数据
     * @param Common_Language $lang 语言对象
     * @return array 栏目配置
     */
    public function getColByListLayout($layout, $meta, $lang)
    {
        $options = array(
            'colNames' => array(),
            'colModel' => array(),
        );

        $id = $meta['db']['id'];
        foreach ($layout as $field => $bool) {
            if (is_array($field)) {
                $fieldMeta = $meta['meta'][$field[0]]['field'][$field[1]];
                $field = $field[0] . '_' . $field[1];
            } else {
                $fieldMeta = $meta['fields'][$field];
            }
            $options['colNames'][] = $lang->t($fieldMeta['title']);
            $options['colModel'][] = array(
                'name' => $field,
                'index' => $field,
            );
            // 隐藏主键
            if ($id == $field) {
                $options['colModel'][count($options['colModel']) - 1]['hidden'] = true;
            }
            // 宽度控制
            if (isset($fieldMeta['list']) && isset($fieldMeta['list']['width'])) {
                $options['colModel'][count($options['colModel']) - 1]['width'] = $fieldMeta['list']['width'];
            }
        }
        return $options;
    }

    public function getLayout(Qwin_Meta_Abstract $meta, array $layout = array(), $relatedName = false)
    {
        foreach ($meta->offsetLoadAsArray('list') as $name => $field) {
            if (true != $field['enabled']) {
                continue;
            }
            $layout[] = $name;

            // 使用order作为键名
//            $order = $field['order'];
//            while (isset($layout[$order])) {
//                $order++;
//            }
//
//            if (!$relatedName) {
//                $layout[$order] = $field['form']['name'];
//            } else {
//                $layout[$order] = array(
//                     $relatedName, $field['form']['name'],
//                );
//            }
        }

//        foreach ($meta->getModelMetaByType('db') as $name => $relatedMeta) {
//            $layout += $this->getLayout($relatedMeta, $layout, $name);
//        }
//
//        // 根据键名排序
//        if (!$relatedName) {
//            ksort($layout);
//        }

        return $layout;
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
