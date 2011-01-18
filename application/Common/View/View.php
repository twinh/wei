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
        $primaryKey = $view->primaryKey;
        $meta = $view->meta;
        $metaHelper = $view->metaHelper;
        $data = $view->data;
        $config = Qwin::run('-config');
        $url = Qwin::run('-url');
        $asc = $config['asc'];
        $lang = Qwin::run('-lang');

        $orderedFeid = $metaHelper->orderField($meta);
        $layout = $metaHelper->getTableLayout($meta, $orderedFeid, 'view', $meta['page']['tableLayout']);

        // 关联列表的数据配置
        //$relatedListConfig = $metaHelper->getRelatedListConfig($meta);
        $relatedListMetaList = $metaHelper->getModelMetadataByType($meta, 'relatedList');
        // 构建每一个的jqgrid数据
        $jgrid = array();
        foreach ($relatedListMetaList as $alias => $relatedMeta) {
            $jgridTmp = array();
            $model = $meta['model'][$alias];
            $jgridTmp['url'] = '?' . $url->arrayKey2Url(array('json' => '1') + $model['set'] + array('qw-search' => $model['foreign'] . ':' . $data[$model['local']]));
            $jgridTmp['url'] = str_replace('\'', '\\\'', $jgridTmp['url']);

            $layout = $metaHelper->getListLayout($relatedMeta);
            foreach ($layout as $field) {
                if (is_array($field)) {
                    $fieldMeta = $relatedMeta['metadata'][$field[0]]['field'][$field[1]];
                    $field = $field[0] . '_' . $field[1];
                } else {
                    $fieldMeta = $relatedMeta['field'][$field];
                }
                $jgridTmp['colNames'][] = $lang->t($fieldMeta['basic']['title']);
                $jgridTmp['colModel'][] = array(
                    'name' => $field,
                    'index' => $field,
                );
                // 隐藏主键
                if ($primaryKey == $field) {
                    $jgridTmp['colModel'][count($jgridTmp['colModel']) - 1]['hidden'] = true;
                }
                // 宽度控制
                if (isset($fieldMeta['list']) && isset($fieldMeta['list']['width'])) {
                    $jgridTmp['colModel'][count($jgridTmp['colModel']) - 1]['width'] = $fieldMeta['list']['width'];
                }
            }
            $jgridTmp['colNames'] = Qwin_Helper_Array::jsonEncode($jgridTmp['colNames']);
            $jgridTmp['colModel'] = Qwin_Helper_Array::jsonEncode($jgridTmp['colModel']);

            $jgrid[$alias] = $jgridTmp;
        }
        p($jgrid);exit;
        $group = $meta['group'];

        $view->setDataList(get_defined_vars());
    }
}