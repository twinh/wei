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
    public function __construct()
    {
        parent::__construct();
        $this->setElement('content', array(
            '<resource><theme>/<defaultNamespace>/element/<defaultModule>/<defaultController>/index<suffix>',
        ));
    }
    
    public function preDisplay()
    {
        // 初始变量,方便调用
        $primaryKey = $this->primaryKey;
        $meta = $this->meta;
        $metaHepler = $this->metaHelper;
        $request = Qwin::run('-request');
        $lang = Qwin::run('-lang');
        $config = Qwin::run('-config');
        $asc = $config['asc'];
        $customLink = $this->customLink;

        // 获取json数据的链接
        $jsonUrl = str_replace('\'', '\\\'', '?' . Qwin::run('-url')->arrayKey2Url(array('json' => '1') + $_GET));

        // 获取栏数据
        $columnName = array();
        $columnSetting = array();
        foreach($this->layout as $field)
        {
            if(is_array($field))
            {
                $fieldMeta = $meta['metadata'][$field[0]]['field'][$field[1]];
                $field = $field[0] . '_' . $field[1];                
            } else {
                $fieldMeta = $meta['field'][$field];
            }
            $columnName[] = $lang->t($fieldMeta['basic']['title']);
            $columnSetting[] = array(
                'name' => $field,
                'index' => $field,
            );
            // 隐藏主键
            if($primaryKey == $field)
            {
                $columnSetting[count($columnSetting) - 1]['hidden'] = true;
            }
            // 宽度控制
            if(isset($fieldMeta['list']) && isset($fieldMeta['list']['width']))
            {
                $columnSetting[count($columnSetting) - 1]['width'] = $fieldMeta['list']['width'];
            }
        }
        $columnName = Qwin_Helper_Array::jsonEncode($columnName);
        $columnSetting = Qwin_Helper_Array::jsonEncode($columnSetting);
        
        // 排序
        if(isset($meta['db']['order']) && !empty($meta['db']['order']))
        {
            $sortName = $meta['db']['order'][0][0];
            $sortOrder = $meta['db']['order'][0][1];
        } else {
            $sortName = $primaryKey;
            $sortOrder = 'DESC';
        }

        /**
         * @todo 当前页数,行数等信息的获取
         */
        //$controller = $this->_data['config']['this'];
        $controller = Qwin::run('-controller');
        $rowNum = intval($request->g($controller->limitName));
        if($rowNum <= 0)
        {
            $rowNum = $this->meta['db']['limit'];
        // 最多同时读取500条记录
        } elseif($rowNum > 500) {
            $rowNum = 500;
        }
        $this->_data = get_defined_vars() + $this->_data;
    }
}