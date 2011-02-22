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
     * @var array $_option          界面的配置选项
     *
     *      -- option               jqGrid的部分配置选项
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
    protected $_option = array(
        'id'            => null,//'ui-jqgrid-table',
        'meta'          => null,
        'lang'          => null,
        'layout'        => array(),
        'option'        => array(
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
     * @var array $_jsonOption      Json数据的配置选项
     *
     *      -- data                 原始数据,一般是从数据库取出,并经过转换
     *
     *      -- layout               布局数组,形式为 order => field
     *
     *      -- primaryKey           主键,主键隐藏 TODO!!!
     *
     *      -- option               jqGrid返回的Json数据的数组
     *
     *          -- page                 当前页面数
     *
     *          -- total                总页面数
     *
     *          -- records              总记录数
     *
     *          -- rows                 记录信息
     */
    protected $_jsonOption = array(
        'data'          => array(),
        'layout'        => array(),
        'primaryKey'    => array(),
        'option'        => array(
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
     * @param array $option 配置选项
     */
    public function render($option)
    {
        // 合并选项
        $option = array_merge($this->_option, $option);
        $jqGrid = array_merge($this->_option['option'], $option['option']);

        $meta = $option['meta'];

        // 设置语言
        if (!$option['lang']) {
            $option['lang'] = Qwin::call('-lang');
        }

        // 设置编号
        if (!$option['id']) {
            $option['id'] = 'ui-jqgrid-' . $meta->getId();
            $this->_id = $option['id'];
        }

        if (!$jqGrid['pager']) {
            $jqGrid['pager'] = '#' . $option['id'] . '-pager';
        }

        // 设置栏目
        if (empty($jqGrid['colNames'])) {
            // 获取并合并布局
            $layout = $this->getLayout($meta);
            if ($option['layout']) {
                $layout = array_intersect($layout, (array)$option['layout']);
            }

            // 根据布局获取栏数据
            $col = $this->getColByListLayout($layout, $meta, $option['lang']);
            $jqGrid['colNames'] = $col['colNames'];
            $jqGrid['colModel'] = $col['colModel'];
        }

        // 设置排序 配置自定义 > 元数据定义 > 主键
        if (!$jqGrid['sortname']) {
            if (is_array($meta['db']['order'][0])) {
                $jqGrid['sortname']  = $meta['db']['order'][0][0];
                $jqGrid['sortorder'] = $meta['db']['order'][0][1];
            } else {
                $jqGrid['sortname']  = $meta['db']['primaryKey'];
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
            ->get('minify')
            ->add($this->_rootPath . '/source/jquery.jqgrid.css')
            ->add($this->_rootPath . '/source/i18n/grid.locale-en.js')
            ->add($this->_rootPath . '/source/jquery.jqgrid.js');

        // 翻译语言
        $jqGrid['emptyrecords'] = $option['lang'][$jqGrid['emptyrecords']];

        // 获取jqGrid与其分页的id号
        $option['pager'] = substr($jqGrid['pager'], 1);

        $jqGridJson = Qwin_Util_Array::jsonEncode($jqGrid);

        require '/view/default.php';
    }

    /**
     * 渲染Json数据
     *
     * @param array $option 配置选项
     */
    public function renderJson($option)
    {
        // 合并选项
        $option = $this->merge($this->_jsonOption, $option);

        // 转换为jqGrid的行数据
        $option['option']['rows'] = $this->filterRowData($option['data'], $option['primaryKey'], $option['layout']);

        return json_encode($option['option']);
    }

    /**
     * 根据列表布局数组,获取栏目配置
     *
     * @param array $layout 列表布局数组
     * @param Qwin_Metadata $meta 元数据
     * @param Common_Language $lang 语言对象
     * @return array 栏目配置
     */
    public function getColByListLayout($layout, $meta, $lang)
    {
        $option = array(
            'colNames' => array(),
            'colModel' => array(),
        );
        $primaryKey = $meta['db']['primaryKey'];
        foreach ($layout as $field) {
            if (is_array($field)) {
                $fieldMeta = $meta['metadata'][$field[0]]['field'][$field[1]];
                $field = $field[0] . '_' . $field[1];
            } else {
                $fieldMeta = $meta['field'][$field];
            }
            $option['colNames'][] = $lang->t($fieldMeta['basic']['title']);
            $option['colModel'][] = array(
                'name' => $field,
                'index' => $field,
            );
            // 隐藏主键
            if ($primaryKey == $field) {
                $option['colModel'][count($option['colModel']) - 1]['hidden'] = true;
            }
            // 宽度控制
            if (isset($fieldMeta['list']) && isset($fieldMeta['list']['width'])) {
                $option['colModel'][count($option['colModel']) - 1]['width'] = $fieldMeta['list']['width'];
            }
        }
        return $option;
    }

    /**
     *  转换为jqGrid的行数据
     *
     * @param array $data 原始数据
     * @param string $primaryKey
     * @param array $layout
     * @return string
     */
    public function filterRowData($data, $primaryKey, $layout)
    {
        $lang = Qwin::call('-lang');
        $i = 0;
        $rowData = array();
        $nullData = '<em>(' . $lang->t('LBL_NULL') .')<em>';
        foreach ($data as $row) {
            $rowData[$i][$primaryKey] = $row[$primaryKey];
            foreach ($layout as $field) {
                if (is_array($field)) {
                    if (isset($row[$field[0]][$field[1]])) {
                        $rowValue = $row[$field[0]][$field[1]];
                    } else {
                        // 使列表 null 类型数据能正确显示
                        $rowValue = $nullData;
                    }
                } else {
                    if (isset($row[$field])) {
                        $rowValue = $row[$field];
                    } else {
                        $rowValue = $nullData;
                    }
                }
                $rowData[$i]['cell'][] = $rowValue;
            }
            $i++;
        }
        return $rowData;
    }

    public function getLayout(Qwin_Metadata $meta, array $layout = null, $relatedName = false)
    {
        null == $layout && $layout = array();
        foreach ($meta['field'] as $name => $field) {
            if (1 != $field['attr']['isList']) {
                continue;
            }

            // 使用order作为键名
            $order = $field['basic']['order'];
            while (isset($layout[$order])) {
                $order++;
            }

            if (!$relatedName) {
                $layout[$order] = $field['form']['name'];
            } else {
                $layout[$order] = array(
                     $relatedName, $field['form']['name'],
                );
            }
        }

        foreach ($meta->getModelMetadataByType('db') as $name => $relatedMeta) {
            $layout += $this->getLayout($relatedMeta, $layout, $name);
        }

        // 根据键名排序
        if (!$relatedName) {
            ksort($layout);
        }

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
