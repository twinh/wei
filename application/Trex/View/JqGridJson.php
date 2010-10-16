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
 * @package     Trex
 * @subpackage  View
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-09-17 18:16:36
 */

class Trex_View_JqGridJson extends Trex_View
{
    public function display()
    {
        // 初始变量,方便调用
        $primaryKey = $this->primaryKey;
        $request = Qwin::run('Qwin_Request');
        $data = $this->data;

        // 转换为jqGrid的行数据
        $data = $this->metaHelper->convertTojqGridData($data, $primaryKey, $this->layout);

        /**
         * @todo 当前页数,行数等信息的获取
         */
        $controller = $this->_data['config']['this'];
        $nowPage = intval($request->g($controller->pageName));
        $nowPage <= 0 && $nowPage = 1;
        $rowNum = intval($request->g($controller->limitName));
        if($rowNum <= 0)
        {
            $rowNum = $this->meta['db']['limit'];
        // 最多同时读取500条记录
        } elseif($rowNum > 500) {
            $rowNum = 500;
        }

        // 输出json数据
        $jsonData = array(
            'page' => $nowPage,
            // 总页面数
            'total' => ceil($this->totalRecord / $rowNum),
            'records' => $this->totalRecord,
            'rows' => $data,
        );
        echo Qwin::run('-arr')->jsonEncode($jsonData);
    }
}
