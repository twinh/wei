<?php
 /**
 * Css
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
 * @subpackage  Wft
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-02-11 23:19
 * @todo        标签的属性配置
 */

/**
 * @see Qwin_Wfe
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
