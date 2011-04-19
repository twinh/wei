<?php
/**
 * Index
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
 * @subpackage  Service
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-10-10 14:16:45
 */

class List_Widget extends Qwin_Widget_Abstract
{
    /**
     * 服务的基本配置
     * @var array
     */
    protected $_defaults = array(
        'module'    => null,
        'list'      => array(),
        'popup'     => false,
        'display'   => true,
    );

    /**
     * 服务处理
     *
     * @param array $options 选项
     * @return array 处理结果
     */
    public function execute(array $options = null)
    {
        // 初始配置
        $options     = $options + $this->_options;
        $meta       = Com_Metadata::getByModule($options['module']);
        $primaryKey = $meta['db']['primaryKey'];

        // 显示哪些域
        $list       = $options['list'];

        // 是否以弹出框形式显示
        $popup      = $options['popup'];

        // 
        if (!$options['display']) {
            return array(
                'result' => true,
                'data' => get_defined_vars(),
            );
        }

        /* @var $jqGridWidget JqGrid_Widget */
        $jqGridWidget   = $this->_widget->get('JqGrid');
        $url            = Qwin::call('-url');
        $request        = Qwin::call('-request');

        $jqGridoptions         = array();

        // 获取json数据的地址
        $jqGridoptions['url'] = $url->build(array('json' => true) + $_GET);

        // 设置Url参数的名称
        $row = intval($request->get('row'));
        $row <= 0 && $row = $meta['db']['limit'];
        $jqGridoptions['rowNum'] = $row;

        // 设置弹出窗口属性
        if ($options['popup']) {
            $popup = array(
                'valueInput'    => $request['popup-value-input'],
                'viewInput'     => $request['popup-view-input'],
                'valueColumn'   => $request['popup-value-column'],
                'viewColumn'    => $request['popup-view-column'],
            );
            $jqGridoptions['multiselect']  = false;
            $jqGridoptions['autowidth']    = false;
            $jqGridoptions['width']        = 800;
        }

        $jqGrid = array(
            'module'    => $options['module'],
            'meta'      => $meta,
            'layout'    => $list,
            'options'    => $jqGridoptions,
        );
        Qwin::call('-view')->assign(get_defined_vars());
    }
}
