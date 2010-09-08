<?php
/**
 * Abstract
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
 * @subpackage  Metadata
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-7-26 14:14:35
 */

abstract class Qwin_Metadata_Element_Abstract extends Qwin_Metadata_Abstract
{
    protected $_data = array();

    public function getSampleData()
    {
        return null;
    }

    public function getData()
    {
        return $this->_data;
    }
    
    /**
     * 设置数据
     *
     * @param array 任意数据
     * @preturn object 当前对象
     */
    public function setData($data)
    {
        $this->_data = $data;
        return $this;
    }

    public function addData($data)
    {
        $this->_data += $data;
        return $this;
    }

    /**
     *
     * @param <type> $metadata
     * @return <type>
     */
    protected function _format($metadata, $name = null)
    {
        return $this->_multiArrayMerge($this->getSampleData(), $metadata);
    }
    
    /**
     * 将数据作为一个整体进行格式化,例如,为数据赋予NULL值等
     */
    public function format()
    {
        $this->_data = $this->_format($this->_data);
        return $this;
    }

    
    /**
     * 将数据作为一个以为数组进行格式化,用于field,model等键名
     */
    protected  function _formatAsArray()
    {
        foreach($this->_data as $key => $row)
        {
            $this->_data[$key] = $this->_format($row, $key);
        }
        return $this;
    }

    protected function _multiArrayMerge($array1, $array2)
    {
        foreach($array2 as $key => $val)
        {
            if(is_array($val))
            {
                !isset($array1[$key]) && $array1[$key] = array();
                $array1[$key] = $this->_multiArrayMerge($array1[$key], $val);
            } else {
                $array1[$key] = $val;
            }
        }
        return $array1;
    }

    public function translateAll($language)
    {
        foreach($this->_data as $data)
        {
            $data->translate($language);
        }
        return $this;
    }

    /**
     * 转换语言
     *
     * @param array $language 用于转换的语言
     * @return object 当前类
     */
    public function translate($language)
    {
        return $this;
    }

    public function unlink($key)
    {
        if(isset($this->_data[$key]))
        {
            unset($this->_data[$key]);
        }
        return $this;
    }
}
