<?php
/**
 * zhcn
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
 * @package     Common
 * @subpackage  Style
 * @author    Twin Huang <twinh@yahoo.cn>
 * @copyright Twin Huang
 * @license   http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @version   2010-5-23 7:29:38 utf-8 中文
 * @since     2010-5-23 7:29:38 utf-8 中文
 */

class Common_Style_Language_Zhcn extends Common_Language_Zhcn
{
    public function __construct()
    {
        parent::__construct();
        $this->_data += array(
            'LBL_FIELD_PATH_NAME' => '路径名称',
            'LBL_FIELD_PICTURE' => '图片',

            'LBL_MODULE_THEME' => '主题',

        );
    }
}
