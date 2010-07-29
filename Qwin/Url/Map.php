<?php
/**
 * Map
 *
 * Copyright (c) 2009-2010 Twin. All rights reserved.
 *
 * LICENSE:
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author    Twin Huang <twinh@yahoo.cn>
 * @copyright Twin Huang
 * @license   http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @version   2010-4-18 15:09:38 utf-8 中文
 * @since     2010-4-18 15:09:38 utf-8 中文
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