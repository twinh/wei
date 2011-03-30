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
 * @package     Com
 * @subpackage  View
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-09-17 18:24:58
 */

class Com_View_View extends Com_View
{
    public function  preDisplay()
    {
        parent::preDisplay();
        $this->setElement('content', '<root>com/basic/view<suffix>');

        // 初始化变量,方便调用
        $primaryKey     = $this->primaryKey;
        $meta           = $this->meta;
        $data           = $this->data;
        $request        = Qwin::call('-request');
        $config         = Qwin::config();
        $url            = Qwin::call('-url');
        $lang           = Qwin::call('-lang');
        $widget         = Qwin::call('-widget');
        
        /* @var $formWidget Form_Widget */
        $formWidget = $widget->get('form');
        $formOption = array(
            'meta'      => $meta,
            'action'    => 'view',
            'data'      => $this->data,
            'view'      => 'view.php',
        );

        /* @var $jqGridWidget JqGrid_Widget */
        $jqGridWidget   = $widget->get('jqgrid');
        $jqGridOptions  = array();

        // 关联列表的数据配置
        //$relatedListConfig = $metaHelper->getRelatedListConfig($meta);
        /* @var $meta Qwin_Application_Metadata */
        $relatedListMetaList = $meta->getModelMetadataByType('relatedList');

        // 构建每一个的jqgrid数据
        $jqGridList = $tabTitle = $moduleLang = array();
        foreach ($relatedListMetaList as $alias => $relatedMeta) {
            $jqGrid = array();
            $model = $meta['model'][$alias];
            
            $lang->appendByModule($model['asc']);

            $tabTitle[$alias] = $lang[$relatedMeta['page']['title']];

            
            // 获取并合并布局
            $listLayout = $jqGridWidget->getLayout($relatedMeta);
            if (null != $model['list']) {
                $listLayout = array_intersect($listLayout, (array)$model['list']);
            }
            // 删除外键域,外键域显示的内容即为当前视图的内容
            $key = array_search($model['foreign'], $listLayout);
            if (false !== $key) {
                unset($listLayout[$key]);
            }
            $col = $jqGridWidget->getColByListLayout($listLayout, $relatedMeta, $lang);
            $option['colNames'] = $col['colNames'];
            $option['colModel'] = $col['colModel'];

            // 获取json数据的地址
            $option['url'] = $url->url(
                array('json' => '1')
                + $model['asc']
                + array('search' => $model['foreign'] . ':' . $data[$model['local']])
                + array('list' => implode(',', $listLayout))
            );

            $jqGrid = array(
                'asc'       => $model['asc'],
                'ascId'     => strtolower(implode('-', $model['asc'])),
                'meta'      => $relatedMeta,
                'layout'    => (array)$model['list'],
                'option'    => $option,
            );

            $jqGridList[$alias] = $jqGrid;
        }
        $group = $meta['group'];

        $operLinks = $widget->get('OperLinks')->render($this);

        $this->assign(get_defined_vars());
    }
}
