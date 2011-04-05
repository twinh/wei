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
 * @package     Com
 * @subpackage  View
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-09-17 18:16:36
 */

class Com_View_JsonList extends Com_View
{
    public function preDisplay()
    {
        // 初始变量,方便调用
        $request = Qwin::call('-request');
        $jqGridWidget = Qwin::call('-widget')->get('JqGrid');

        // 获取并合并布局
        $layout = $jqGridWidget->getLayout($this->meta);
        if ($this->listFields) {
            $layout = array_intersect($layout, (array)$this->listFields);
        }

        // 通过jqGrid微件获取数据
        $json = $jqGridWidget->renderJson(array(
            'data'          => $this->data,
            'layout'        => $layout,
            'primaryKey'    => $this->meta['db']['primaryKey'],
            'options'        => array(
                'page'      => $this->options['page'],
                'total'     => ceil($this->total / $this->options['row']),
                'records'   => $this->total,
            ),
        ));

        // TODO 输出型视图
        echo $json;
        $this->setDisplayed();
    }
}
