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
        $meta       = $this->meta;
        $request    = Qwin::call('-request');
        $lang       = Qwin::call('-lang');
        $url        = Qwin::call('-url');
        $asc        = Qwin::config('asc');

        /* @var $jqGridWidget JqGrid_Widget */
        $jqGridWidget   = $this->widget->get('jqgrid');

        $option         = array();
      
        // 获取json数据的地址
        $option['url'] = $url->url(array('json' => true) + $_GET);

        // 设置Url参数的名称
        $option['rowNum']        = $request->getLimit();

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
            'asc'       => $asc,
            'ascId'     => strtolower(implode('-', $asc)),
            'meta'      => $meta,
            'layout'    => $this->list,
            'option'    => $option,
        );
        
        $this->assign(get_defined_vars());
    }
}
