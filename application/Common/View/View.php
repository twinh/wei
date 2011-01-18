<?php
/**
 * View
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
 * @since       2010-09-17 18:24:58
 */

class Common_View_View extends Qwin_Application_View_Processer
{
    public function __construct(Qwin_Application_View $view)
    {
        // 初始化变量,方便调用
        $primaryKey     = $view->primaryKey;
        $meta           = $view->meta;
        $metaHelper     = $view->metaHelper;
        $data           = $view->data;
        $request        = Qwin::run('#request');
        $config         = Qwin::run('-config');
        $url            = Qwin::run('-url');
        $asc            = $config['asc'];
        $lang           = Qwin::run('-lang');
        $langHelper     = Qwin::run('Common_Helper_Language');
        $jqGridHepler   = new Common_Helper_JqGrid();
        $jqGrid         = array();

        $orderedFeid = $metaHelper->orderField($meta);
        $layout = $metaHelper->getTableLayout($meta, $orderedFeid, 'view', $meta['page']['tableLayout']);

        // 关联列表的数据配置
        //$relatedListConfig = $metaHelper->getRelatedListConfig($meta);
        $relatedListMetaList = $metaHelper->getModelMetadataByType($meta, 'relatedList');
        // 构建每一个的jqgrid数据
        $jqGridList = $jqGridJsonList = $tabTitle = $moduleLang = array();
        foreach ($relatedListMetaList as $alias => $relatedMeta) {
            $jqGrid = array();
            $model = $meta['model'][$alias];
            $relatedAsc = $model['set'];
            $uniqueId = strtolower(implode('-', $relatedAsc));
            $relatedpPrimaryKey = $relatedMeta['db']['primaryKey'];
            $relatedLang = $langHelper->getObjectByAsc($relatedAsc);

            $tabTitle[$alias] = $relatedLang[$relatedMeta['page']['title']];

            // 获取栏数据
            $listLayout = $metaHelper->getListLayout($relatedMeta);
            if (null != $model['list']) {
                $listLayout = array_intersect($listLayout, (array)$model['list']);
            }
            $listLayoutQuery = implode(',', $listLayout);
            $col = $jqGridHepler->getColByListLayout($listLayout, $relatedMeta, $lang);
            $jqGrid['colNames'] = $col['colNames'];
            $jqGrid['colModel'] = $col['colModel'];

            // 获取json数据的地址
            $jqGrid['url'] = '?' . $url->arrayKey2Url(array('json' => '1') + $model['set'] + array('qw-search' => $model['foreign'] . ':' . $data[$model['local']]) + array('qw-list' => $listLayoutQuery));

            // 设置排序
            if(!empty($relatedMeta['db']['order'])) {
                $jqGrid['sortname']  = $relatedMeta['db']['order'][0][0];
                $jqGrid['sortorder'] = $relatedMeta['db']['order'][0][1];
            } else {
                $jqGrid['sortname']  = $relatedpPrimaryKey;
                $jqGrid['sortorder'] = 'DESC';
            }

            // 设置Url参数的名称
            $jqGrid['rowNum']        = $request->getLimit();
            $jqGrid['rowNum']        <= 0 && $jqGrid['rowNum'] = $meta['db']['limit'];
            $jqGrid['prmNames'] = array(
                'page'              => $request->getOption('page'),
                'rows'              => $request->getOption('row'),
                'sort'              => $request->getOption('orderField'),
                'order'             => $request->getOption('orderType'),
                'search'            => '_search',
            );
            /*$jqGrid['prmNames'] = array(
                'page'              => 'qw-' . $uniqueId . '-page',
                'rows'              => 'qw-' . $uniqueId . '-row',
                'sort'              => 'qw-' . $uniqueId . '-order-field',
                'order'             => 'qw-' . $uniqueId . '-order-type',
                'search'            => '_search',
            );*/

            //
            $jqGrid['toolbar'] = false;

            $jqGrid = $jqGridHepler->render($jqGrid);
            $jqGridList[$alias] = $jqGrid;
            $jqGridListJson[$alias] = Qwin_Helper_Array::jsonEncode($jqGrid);
        }
        $group = $meta['group'];

        $view->setDataList(get_defined_vars());
    }
}