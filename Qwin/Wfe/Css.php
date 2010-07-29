<?php
 /**
 * Css
 *
 * Copyright (c) 2009 Twin. All rights reserved.
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
 * @version   2010-02-11 23:19 utf-8 中文
 * @since     2010-02-11 23:19 utf-8 中文
 * @todo      标签的属性配置
 */

require_once 'Qwin/Wfe.php';

class Qwin_Wfe_Css extends Qwin_Wfe
{
    private $_file = array();
    
    
    public function add($file)
    {
        if(file_exists($file) && !in_array($file, $this->_file))
        {
            $this->_file[$file] = $file;
        }
    }
    
    public function packAll($mode = 'link')
    {
        $data = '';
        //$mode = qw('-arr')->forceInArray($mode, array('link', 'import'));
        if($mode == 'link')
        {
            foreach($this->_file as $val)
            {
                $data .= '<link rel="stylesheet" type="text/css" href="' . $val . '" />' . $this->line_break;
            }
        } else {
            foreach($this->_file as $val)
            {
                $data .= '<style type="text/css">@import url(' . $val . ');</style>' . $this->line_break;
            }
        }
        return $data;
    }
}
