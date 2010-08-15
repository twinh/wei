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
 * @package     Qwin
 * @subpackage  
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-08-14 15:32:17
 */

class Default_Common_View_JqGrid extends Default_View
{
    public function display()
    {
        /**
         * 初始变量,方便调用
         */
        $primaryKey = $this->primaryKey;
        $meta = $this->meta;

        /**
         * 数据转换
         */
        // 获取json数据的链接
        $urlGet = $_GET;
        $urlGet['action'] = 'List';
        $jsonUrl = '?' . Qwin::run('-url')->arrayKey2Url($urlGet);

        // 获取栏数据
        $columnName = array();
        $columnSetting = array();
        foreach($this->listField as $field)
        {
            $columnName[] = $meta['field'][$field]['basic']['title'];
            $columnSetting[] = array(
                'name' => $field,
                'index' => $field,
            );
            if($primaryKey == $field)
            {
                $columnSetting[count($columnSetting) - 1]['hidden'] = true;
            }
        }

        // 排序
        if(isset($meta['db']['order']) && !empty($meta['db']['order']))
        {
            $sortName = $meta['db']['order'][0][0];
            $sortOrder = $meta['db']['order'][0][1];
        } else {
            $sortName = $primaryKey;
            $sortOrder = 'DESC';
        }

        require_once $this->_layout;
 

/*
        $col_setting_arr = array();
        $col_name_arr = array();
        foreach($this->_set['field'] as $val)
        {
            if($val['attr']['isList'] == true)
            {
                $col_setting_arr[] = array(
                    'name' => $val['form']['name'],
                    'index' => $val['form']['name'],
                );
                if($this->_set['db']['primaryKey'] == $val['form']['name'])
                {
                    $col_setting_arr[count($col_setting_arr) - 1]['hidden'] = true;
                }
                $col_name_arr[] = $val['basic']['title'];
            }
        }


        $jqgrid = Qwin::run('Qwin_JQuery_JqGrid', $meta);
        $json_url = $jqgrid->getJsonUrl();
        $col_data = $jqgrid->getColData();

        p($json_url);
        p($col_data);
        exit;
        $ini = Qwin::run('-ini');
        $this->_request = Qwin::run('-gpc');
        $meta = &$this->_meta;

        $primaryKey = $meta['db']['primaryKey'];
        // 修改主键域配置,以适应jqgrid
        $this->_meta['field'][$primaryKey]['list'] = array(
            'isUrlQuery' => false,
            'isList' => true,
            'isSqlField' => true,
            'isSqlQuery' => true,
        );

        // 加载关联模型,元数据
        $this->_meta->loadRelatedData($meta['model']);
        // 连接元数据
        $meta = $this->_meta->connetMetadata($meta);
        // 排序
        $meta['field'] = $this->_meta->orderSettingArr($meta['field']);

        $jqgrid = Qwin::run('Qwin_JQuery_JqGrid', $meta);
        // 加载的url地址
        $json_url = $jqgrid->getJsonUrl();
        $col_data = $jqgrid->getColData();
        $col_name = Qwin::run('-arr')->jsonEncode($col_data['col_name']);
        $col_setting = Qwin::run('-arr')->jsonEncode($col_data['col_setting']);

        // 排序
        if(isset($meta['db']['order']) && !empty($meta['db']['order']))
        {
            $sortName = $meta['db']['order'][0][0];
            $sortOrder = $meta['db']['order'][0][1];
        } else {
            $sortName = $primaryKey;
            $sortOrder = 'DESC';
        }

        // 初始化视图变量数组
        $this->__view = array(
            'col_name' => $col_name,
            'col_setting' => $col_setting,
            'sortName' => $sortName,
            'sortOrder' => $sortOrder,
            'rowNum' => $meta['page']['rowNum'],
            'json_url' => $json_url,
            'primary_key' => $meta['db']['primaryKey'],
        );

        // 初始化控制面板中心内容的视图变量数组,加载控制面板视图
        $this->__view_element = array(
            'content' => RESOURCE_PATH . '/php/View/Element/DefaultList.php',
        );
        $this->loadView(Qwin::run('-ini')->load('Resource/View/Layout/DefaultControlPanel', false));*/
    }
}
