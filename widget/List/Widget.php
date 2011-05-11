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
     * @var array           默认选项
     * 
     *      -- meta         元数据对象
     * 
     *      -- id           主键的值
     * 
     *      -- data         初始值
     * 
     *      -- sanitise     转换配置
     * 
     *      -- display      是否显示视图
     */
    protected $_defaults = array(
        'meta'      => null,
        'name'      => 'list',
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
    public function render($options)
    {
        // 初始配置
        $options    = (array)$options + $this->_options;
        
        // 检查元数据是否合法
        $meta = $options['meta'];
        if (false === Qwin_Metadata::isValid($meta)) {
            $this->e('ERR_META_NOT_DEFINED');
        }
        
        // 检查元数据中是否包含表单定义
        if (!$meta->offsetLoad($options['name'], 'list')) {
            return $this->e('ERR_META_OFFSET_NOT_FOUND', $options['name']);
        }
         
        // 显示哪些域
        $list = $options['list'];

        // 是否以弹出框形式显示
        $popup = $options['popup'];

        // 不显示视图，直接返回数据
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

        $jqGridOptions  = array();

        // 获取json数据的地址
        $jqGridOptions['url'] = $url->build(array('json' => true) + $_GET);

        // 设置Url参数的名称
        $row = intval($request->get('row'));
        $row <= 0 && $row = $meta['db']['limit'];
        $jqGridOptions['rowNum'] = $row;

        // 设置弹出窗口属性
        if ($options['popup']) {
            $popup = array(
                'valueInput'    => $request['popup-value-input'],
                'viewInput'     => $request['popup-view-input'],
                'valueColumn'   => $request['popup-value-column'],
                'viewColumn'    => $request['popup-view-column'],
            );
            $jqGridOptions['multiselect']  = false;
            $jqGridOptions['autowidth']    = false;
            $jqGridOptions['width']        = 800;
        }

        $jqGrid = array(
            'meta'      => $meta,
            'list'      => $meta[$options['name']],
            'layout'    => $list,
            'options'    => $jqGridOptions,
        );
        qw_p($jqGrid);
        Qwin::call('-view')->assign(get_defined_vars());
    }
}
