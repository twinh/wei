<?php
/**
 * CommonClass
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
 * @version   2010-7-17 10:53:31
 * @since     2010-7-17 10:53:31
 */

class Project_Helper_CommonClass
{
    /**
     * 语言标识
     * @var string
     */
    protected $_language;

    /**
     * 数据缓存
     * @var array
     */
    protected $_data;


    function __construct()
    {
        
    }

    public function get($name)
    {
        if(!isset($this->_language))
        {
            $lang = Qwin::run('-controller')->getLanguage();
            $this->_language = Qwin::run('Qwin_Language')->toStandardStyle($lang);
        }

        if(!isset($this->_data[$this->_language]))
        {
            $this->_data[$this->_language] = array();
        }

        if(!isset($this->_data[$this->_language][$name]))
        {
            $this->_data[$this->_language][$name] = require QWIN_RESOURCE_PATH . '/class/' . $this->_language . '/' . $name . '.php';
        }
        return $this->_data[$this->_language][$name];
    }

    public function convert($value, $name)
    {
        $data = $this->get($name);
        if(isset($data[$value]))
        {
            return $data[$value];
        }
        return $value;
    }

    public function write($data)
    {
        $codeList = array();
        $code = explode("\r\n", $data['code']);
        foreach($code as &$item)
        {
             $tmp = explode(':', trim($item));
             $codeList[$tmp[0]] = $tmp[1];
        }
        $cachePath = QWIN_RESOURCE_PATH . '/class/' . $data['language'] . '/' . $data['sign'] . '.php';
        Qwin_Helper_File::writeAsArray($codeList, $cachePath);
    }
}
