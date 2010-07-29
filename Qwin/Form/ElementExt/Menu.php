<?php
/**
 * Menu
 *
 * Copyright (c) 2009-2010 Twin Huang. All rights reserved.
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
 * @author    Twin Huang <Twin Huang>
 * @copyright Twin Huang
 * @license   http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @version   2010-3-21 14:58:55
 * @since     2010-3-21 14:58:55 utf-8 中文
 */

class Qwin_Form_ElementExt_Menu extends Qwin_Form
{
    public function MenuType($pub_set, $pri_set, $value, $data)
    {
        require 'QwinView/Form/MenuType.php';
        
        return $data;
    }
}

