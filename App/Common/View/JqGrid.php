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

class Common_View_JqGrid extends Common_View
{   
    public function preDisplay()
    {
        // 初始变量,方便调用
        $primaryKey     = $this->primaryKey;
        $meta           = $this->meta;
        $metaHepler     = $this->metaHelper;
        $request        = Qwin::run('#request');
        $lang           = Qwin::run('-lang');
        $config         = Qwin::run('-config');
        $url            = Qwin::run('-url');
        $asc            = $config['asc'];
        $jqGridHepler   = new Common_Helper_JqGrid();
        $jqGrid         = array();

        // 设置应用结构配置
        $jqGrid['asc'] = $asc;
        
        // 获取json数据的地址
        $jqGrid['url'] = $url->url(array('json' => '1') + $_GET);

        // 获取栏数据
        $col = $jqGridHepler->getColByListLayout($this->layout, $meta, $lang);
        $jqGrid['colNames'] = $col['colNames'];
        $jqGrid['colModel'] = $col['colModel'];
        
        // 设置排序
        if(!empty($meta['db']['order'])) {
            $jqGrid['sortname']  = $meta['db']['order'][0][0];
            $jqGrid['sortorder'] = $meta['db']['order'][0][1];
        } else {
            $jqGrid['sortname']  = $primaryKey;
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

        // 设置弹出窗口属性
        if ($this->isPopup) {
            $popup = array(
                'valueInput'    => $request['qw-popup-value-input'],
                'viewInput'     => $request['qw-popup-view-input'],
                'valueColumn'   => $request['qw-popup-value-column'],
                'viewColumn'    => $request['qw-popup-view-column'],
            );
            $jqGrid['multiselect']  = false;
            $jqGrid['autowidth']    = false;
            $jqGrid['width']        = 800;
        }

        $jqGrid = $jqGridHepler->render($jqGrid);
        $jqGridJson = Qwin_Helper_Array::jsonEncode($jqGrid);

        $this->assign(get_defined_vars());
    }
}
