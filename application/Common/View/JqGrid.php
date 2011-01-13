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
 * @package     Common
 * @subpackage  View
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-09-17 18:15:51
 */

class Common_View_JqGrid extends Qwin_Application_View_Processer
{   
    public function __construct(Qwin_Application_View $view)
    {
        // 初始变量,方便调用
        $primaryKey = $view->primaryKey;
        $meta       = $view->meta;
        $metaHepler = $view->metaHelper;
        $request    = Qwin::run('#request');
        $lang       = Qwin::run('-lang');
        $config     = Qwin::run('-config');
        $url        = Qwin::run('-url');
        $asc        = $config['asc'];

        /**
         * @var array $jgrid            jgrid 配置,不与js完全一致
         *
         *      -- url                  获取json数据的链接
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
         *      -- page                 分页的查询名称
         *
         *      -- rows                 每页显示数目的查询名称
         *
         *      -- sort                 排序域查询的名称
         *
         *      -- order                排序类型的查询名称
         *
         *      -- search               搜索的查询名称
         *
         *      -- multiselect          是否允许多选
         */
        $jgrid = array();
        $jgrid['url'] = str_replace('\'', '\\\'', '?' . $url->arrayKey2Url(array('json' => '1') + $_GET));

        // 获取栏数据
        $jgrid['colNames'] = array();
        $jgrid['colModel'] = array();
        foreach ($view->layout as $field) {
            if (is_array($field)) {
                $fieldMeta = $meta['metadata'][$field[0]]['field'][$field[1]];
                $field = $field[0] . '_' . $field[1];                
            } else {
                $fieldMeta = $meta['field'][$field];
            }
            $jgrid['colNames'][] = $lang->t($fieldMeta['basic']['title']);
            $jgrid['colModel'][] = array(
                'name' => $field,
                'index' => $field,
            );
            // 隐藏主键
            if ($primaryKey == $field) {
                $jgrid['colModel'][count($jgrid['colModel']) - 1]['hidden'] = true;
            }
            // 宽度控制
            if (isset($fieldMeta['list']) && isset($fieldMeta['list']['width'])) {
                $jgrid['colModel'][count($jgrid['colModel']) - 1]['width'] = $fieldMeta['list']['width'];
            }
        }
        $jgrid['colNames'] = Qwin_Helper_Array::jsonEncode($jgrid['colNames']);
        $jgrid['colModel'] = Qwin_Helper_Array::jsonEncode($jgrid['colModel']);
        
        // 排序
        if(!empty($meta['db']['order'])) {
            $jgrid['sortname']  = $meta['db']['order'][0][0];
            $jgrid['sortorder'] = $meta['db']['order'][0][1];
        } else {
            $jgrid['sortname']  = $primaryKey;
            $jgrid['sortorder'] = 'DESC';
        }

        $jgrid['rowNum']        = $request->getLimit();
        $jgrid['rowNum']        <= 0 && $jgrid['rowNum'] = $view->meta['db']['limit'];
        $jgrid['page']          = $request->getOption('page');
        $jgrid['rows']          = $request->getOption('row');
        $jgrid['sort']          = $request->getOption('orderField');
        $jgrid['order']         = $request->getOption('orderType');
        $jgrid['search']        = $request->getOption('search');
        $jgrid['multiselect']   = true;

        $view->setDataList(get_defined_vars());
    }
}