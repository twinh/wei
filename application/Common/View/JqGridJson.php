<?php
/**
 * JqGridJson
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
 * @since       2010-09-17 18:16:36
 */

class Common_View_JqGridJson extends Qwin_Application_View_Processer
{
    public function __construct(Qwin_Application_View $view)
    {
        // 初始变量,方便调用
        $primaryKey = $view->primaryKey;
        $request = Qwin::run('#request');
        $data = $view->data;
        $jqGridHelper = new Common_Helper_JqGrid();

        // 转换为jqGrid的行数据
        $data = $jqGridHelper->convertRowData($data, $primaryKey, $view['layout']);

        /**
         * @var array       jqGrid的Json数据数组
         *
         *      -- page     当前页面数
         *
         *      -- total    总页面数
         *
         *      -- records  总记录数
         *
         *      -- rows     记录信息
         */
        $jsonData = array(
            'page' => $request->getPage(),
            'total' => ceil($view->totalRecord / $request->getLimit()),
            'records' => $view->totalRecord,
            'rows' => $data,
        );

        // TODO 输出型视图
        echo Qwin_Helper_Array::jsonEncode($jsonData);
        $view->setDisplayed();
    }
}
