<?php
/**
 * Metadata
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
 * @version   2010-7-28 17:26:04
 * @since     2010-7-28 17:26:04
 */

class Trex_Metadata extends Qwin_Trex_Metadata
{
    /**
     * 设置基本的元数据,包括编号,创建时间,修改时间和操作.
     */
    public function setCommonMetadata()
    {
         return $this->setIdMetadata()
             ->setDateMetadata()
             ->setOperationMetadata();
    }

    /**
     * 设置编号域的元数据配置,编号是最为常见的域
     *
     * @return obejct 当前类
     */
    public function setIdMetadata()
    {
        $this->addField(array(
            'id' => array(
                'basic' => array(
                    'order' => -1,
                ),
                'form' => array(
                    '_type' => 'hidden',
                    'name' => 'id',
                ),
                'attr' => array(
                    'isListLink' => 0,
                    'isList' => 1,
                    'isDbField' => 1,
                    'isDbQuery' => 1,
                    'isReadonly' => 0,
                ),
            ),
        ));
        return $this;
    }

    /**
     * 设置日期的元数据配置,主要包括创建日期,修改日期两项
     *
     * @return object 当前类
     */
    public function setDateMetadata()
    {
        $this->addField(array(
            'date_created' => array(
                'basic' => array(
                    'order' => 1000,
                ),
                'form' => array(
                    '_type' => 'custom',
                    'name' => 'date_created',
                ),
                'attr' => array(
                    'isListLink' => 0,
                    'isList' => 1,
                    'isDbField' => 1,
                    'isDbQuery' => 1,
                    'isReadonly' => 1,
                ),
            ),
            'date_modified' => array(
                'basic' => array(
                    'title' => 'LBL_FIELD_DATE_MODIFIED',
                    'descrip' => '',
                    'order' => 1020,
                    'group' => 'LBL_GROUP_BASIC_DATA',
                ),
                'form' => array(
                    '_type' => 'custom',
                    'name' => 'date_modified',
                ),
                'attr' => array(
                    'isListLink' => 0,
                    'isList' => 1,
                    'isDbField' => 1,
                    'isDbQuery' => 1,
                ),
            ),
        ));
        return $this;
    }

    /**
     * 设置操作域的元数据配置,操作域主要用于列表
     *
     * @return obejct 当前类
     */
    public function setOperationMetadata()
    {
        $this->addField(array(
            'operation' => array(
                'basic' => array(
                    'order' => 1040,
                ),
                'form' => array(
                    '_type' => 'custom',
                    'name' => 'operation',
                ),
                'attr' => array(
                    'isListLink' => 0,
                    'isList' => 1,
                    'isDbField' => 0,
                    'isDbQuery' => 0,
                    'isView' => 0,
                ),
            ),
        ));
        return $this;
    }
}
