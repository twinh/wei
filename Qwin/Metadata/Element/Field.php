<?php
/**
 * Field
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
 * @version   2010-7-26 14:07:07
 * @since     2010-7-26 14:07:07
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