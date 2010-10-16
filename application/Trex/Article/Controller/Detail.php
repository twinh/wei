<?php
/**
 * Detail
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
 * @package     Trex
 * @subpackage  Article
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-10-13 20:28:20
 */

class Trex_Article_Controller_Detail extends Trex_Controller
{
    public function convertDbMeta($value, $name, $data, $copyData)
    {
        return serialize(array(
            'keywords' => $copyData['meta_keywords'],
            'description' => $copyData['meta_description'],
        ));
    }

    public function convertEditMeta($value, $name, $data, $copyData)
    {
        return unserialize($value);
    }

    public function convertEditMetaKeywords($value, $name, $data, $copyData)
    {
        return $data['meta']['keywords'];
    }

    public function convertEditMetaDescription($value, $name, $data, $copyData)
    {
        return $data['meta']['description'];
    }

    public function convertViewMeta($value, $name, $data, $copyData)
    {
        return unserialize($value);
    }

    public function convertViewMetaKeywords($value, $name, $data, $copyData)
    {
        return $data['meta']['keywords'];
    }

    public function convertViewMetaDescription($value, $name, $data, $copyData)
    {
        return $data['meta']['description'];
    }

    /**
     * 在入库操作下,转换编号
     *
     * @param mixed 当前域的值
     * @param string 当前域的名称
     * @param array $data 已转换过的当前记录的值
     * @param array $cpoyData 未转换过的当前记录的值
     * @return string 当前域的新值
     */
    public function convertDbId($value, $name, $data, $copyData)
    {
        return Qwin::run('Qwin_converter_String')->getUuid($value);
    }
}
