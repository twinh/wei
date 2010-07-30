<?php
/**
 * Map
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
 * @subpackage  Url
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-4-18 15:09:38
 */

/**
 * @see Qwin_Url
 */
require_once 'Qwin/Url.php';

class Qwin_Url_Map extends Qwin_Url
{
/*
例如 url地址为 Index.php?namespace=System&module=Module&controller=Module&action=Edit&id=e0bd2864-9b83-102d-9cb5-f7d95932f01d
 *
 * 经过转换,得到数据为
 * $_get = array(
 *    n => System,
 *    m => Module,
 *    c => Module,
 *    a => Edit,
 * );
 *
 * 可以还原数据
 *
 * 双键一用
 * n 和 namespace 是一样的, 绑定?
 *
 * $this










    */
    private $_map = array(
        'namespace' => 'n',
        'module' => 'm',
        'controller' => 'c',
        'action' => 'a',
        'data' => 'd',
    );

    public function update()
    {
        foreach($this->_map as $key => $val)
        {
            
        }
    }

    public function setGetData($get)
    {
        //$this
    }

    public function __construct()
    {

    }
}
