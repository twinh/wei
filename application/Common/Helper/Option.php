<?php
/**
 * Option
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
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2011-01-20 14:38:02
 */

class Common_Helper_Option
{
        /**
     * 语言标识
     * @var string
     */
    protected $_lang;

    /**
     * 数据缓存
     * @var array
     */
    protected $_data;

    public function get($name)
    {
        if(!isset($this->_lang))
        {
            $config = Qwin::run('-config');
            $languageName = Qwin::run('Common_Helper_Language')->getName($config['asc']);
            $this->_lang = Qwin::run('Qwin_Language')->toStandardStyle($languageName);
        }

        if(!isset($this->_data[$this->_lang]))
        {
            $this->_data[$this->_lang] = array();
        }

        if(!isset($this->_data[$this->_lang][$name]))
        {
            $this->_data[$this->_lang][$name] = require QWIN_RESOURCE_PATH . '/class/' . $this->_lang . '/' . $name . '.php';
        }
        return $this->_data[$this->_lang][$name];
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

    public function delete($data)
    {
        foreach($data as $row)
        {
            $cachePath = QWIN_RESOURCE_PATH . '/class/' . $row['language'] . '/' . $row['sign'] . '.php';
            if(file_exists($cachePath))
            {
                unlink($cachePath);
            }
        }
    }
}