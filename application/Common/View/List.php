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

class Common_View_List extends Common_View
{   
    public function preDisplay()
    {
        parent::preDisplay();
        // 初始变量,方便调用
        $manager        = Qwin::call('-manager');
        $primaryKey     = $this->primaryKey;
        $meta           = $this->meta;
        $metaHepler     = $this->metaHelper;
        $request        = Qwin::call('-request');
        $lang           = Qwin::call('-lang');
        $url            = Qwin::call('-url');
        $asc            = Qwin::config('asc');
        $ascString      = strtolower(implode('-', $asc));

        $jqGridWidget = $this->widget->get('jqgrid');

        $option         = array();
      
        // 获取json数据的地址
        $option['url'] = $url->url(array('json' => '1') + $_GET);

        // 获取并合并布局
        $layout = $this->metaHelper->getListLayout($meta);
        if ($this->listField) {
            $layout = array_intersect($layout, (array)$this->listField);
        }

        // 根据布局获取栏数据
        $col = $jqGridWidget->getColByListLayout($layout, $meta, $lang);
        $option['colNames'] = $col['colNames'];
        $option['colModel'] = $col['colModel'];
        
        // 设置排序
        if(!empty($meta['db']['order'])) {
            $option['sortname']  = $meta['db']['order'][0][0];
            $option['sortorder'] = $meta['db']['order'][0][1];
        } else {
            $option['sortname']  = $primaryKey;
            $option['sortorder'] = 'DESC';
        }

        // 设置Url参数的名称
        $option['rowNum']        = $request->getLimit();
        $option['rowNum']        <= 0 && $option['rowNum'] = $meta['db']['limit'];
        $option['prmNames'] = array(
            'page'              => $request->getOption('page'),
            'rows'              => $request->getOption('row'),
            'sort'              => $request->getOption('orderField'),
            'order'             => $request->getOption('orderType'),
        );

        // 设置弹出窗口属性
        if ($this->isPopup) {
            $popup = array(
                'valueInput'    => $request['popup-value-input'],
                'viewInput'     => $request['popup-view-input'],
                'valueColumn'   => $request['popup-value-column'],
                'viewColumn'    => $request['popup-view-column'],
            );
            $option['multiselect']  = false;
            $option['autowidth']    = false;
            $option['width']        = 800;
        }

        $jqGrid = array(
            'id'     => $ascString,
            'asc'    => $asc,
            'option' => $option,
        );
        
        $this->assign(get_defined_vars());
    }
}
