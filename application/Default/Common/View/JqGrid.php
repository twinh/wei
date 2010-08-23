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
 * @package     Default
 * @subpackage  Common
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-08-14 15:32:17
 */

class Default_Common_View_JqGrid extends Default_View
{
    public function  __construct()
    {
        parent::__construct();
        $this->setElement('content', QWIN_RESOURCE_PATH . '/view/theme/' . $this->_theme . '/element/common-list.php');
    }

    public function display()
    {
        /**
         * 初始变量,方便调用
         */
        $primaryKey = $this->primaryKey;
        $meta = $this->meta;
        $arrayHelper = Qwin::run('-arr');
        $request = Qwin::run('Qwin_Request');
        $lang = Qwin::run('-lang');
        $set = Qwin::run('-ini')->getSet();

        /**
         * 数据转换
         */
        // 获取json数据的链接
        $urlGet = array('action' => 'List') + $_GET;
        $jsonUrl = '?' . Qwin::run('-url')->arrayKey2Url($urlGet);

        // 获取栏数据
        $columnName = array();
        $columnSetting = array();
        foreach($this->listField as $field)
        {
            $columnName[] = $lang->t($meta['field'][$field]['basic']['title']);
            $columnSetting[] = array(
                'name' => $field,
                'index' => $field,
            );
            if($primaryKey == $field)
            {
                $columnSetting[count($columnSetting) - 1]['hidden'] = true;
            }
        }
        $columnName = $arrayHelper->jsonEncode($columnName);
        $columnSetting = $arrayHelper->jsonEncode($columnSetting);
        
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
        $rowNum = intval($request->g('row'));
        if($rowNum <= 0)
        {
            $rowNum = $this->meta['db']['limit'];
        // 最多同时读取500条记录
        } elseif($rowNum > 500) {
            $rowNum = 500;
        }
        
        require_once $this->_layout;
    }
}
