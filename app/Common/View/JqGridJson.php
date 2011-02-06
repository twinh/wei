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

class Common_View_JqGridJson extends Common_View
{
    public function preDisplay()
    {
        // 初始变量,方便调用
        $request = Qwin::call('#request');
        $jqGridWidget = $this->widget->get('jqgrid');

        $json = $jqGridWidget->renderJson(array(
            'data'          => $this->data,
            'layout'        => $this->layout,
            'primaryKey'    => $this->primaryKey,
            'option'        => array(
                'page'      => $request->getPage(),
                'total'     => ceil($this->totalRecord / $request->getLimit()),
                'records'   => $this->totalRecord,
            ),
        ));

        // TODO 输出型视图
        echo $json;
        $this->setDisplayed();
    }
}
