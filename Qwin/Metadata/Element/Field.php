<?php
/**
 * Field
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
 * @since       2010-7-26 14:07:07
 */

class Qwin_Metadata_Element_Field extends Qwin_Metadata_Element_Abstract
{
    public function getSampleData()
    {
        return array(
            'basic' => array(
                'title' => 'LBL_FIELD_TITLE',
                'descrip' => '',
                'order' => 0,
                'group' => NULL,
            ),
            'form' => array(
                '_type' => NULL,
                '_typeExt' => NULL,
                '_resource' => NULL,
                '_resourceGetter' => NULL,
                '_icon' => NULL,
                'name' => NULL,
                'id' => NULL,
                'value' => NULL,
            ),
            'attr' => array(
                'isUrlQuery' => false,
                'isList' => false,
                'isSqlField' => false,
                'isSqlQuery' => false,
                'isReadonly' => false,
            ),
            'conversion' => array(
                'add' => NULL,
                'edit' => NULL,
                'list' => NULL,
                'db' => NULL,
            ),
            'validation' => array(

            ),
        );
    }

    public function format()
    {
        return $this->_formatAsArray();
    }

    /**
     * 筛选符合属性的域
     *
     * @param 合法的属性组成的数组
     * @param 非法的属性组成的数组
     * @return array 符合要求的的域组成的数组
     */
    public function getAttrList(array $allowAttr, array $banAttr = NULL)
    {
        //$allowAttr = (array)$allowAttr;
        //$banAttr = (array)$banAttr;
        $tmpArr = array();
        $result = array();
        foreach($allowAttr as $attr)
        {
            $tmpArr[$attr] = true;
        }
        foreach($banAttr as $attr)
        {
            $tmpArr[$attr] = false;
        }
        foreach($this->_data as $field)
        {
            if($tmpArr == array_intersect_assoc($tmpArr, $field['attr']))
            {
                $result[$field['form']['name']] = $field['form']['name'];
            }
        }
        return $result;
    }

    public function toDoctrine()
    {
        
    }

    public function addValidator()
    {

    }

    public function addValidatorRule()
    {

    }

    public function setAttr()
    {

    }
}