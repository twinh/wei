<?php
/**
 * Article
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
 * @version   2010-5-26 22:52:49 utf-8 中文
 * @since     2010-5-26 22:52:49 utf-8 中文
 */

class Default_Product_Metadata_Product extends Qwin_Trex_Metadata
{
    public function defaultMetadata()
    {
        return array(
            // 基本属性
            'field' => array(
                'id' => array(
                    'basic' => array(
                        'title' => 'LBL_FIELD_ID',
                        'descrip' => '',
                        'order' => 0,
                        'group' => 'LBL_GROUP_BASIC_DATA',
                    ),
                    'form' => array(
                        '_type' => 'hidden',
                        '_typeExt' => '',
                        '_value' => '',
                        'name' => 'id',
                    ),
                    'attr' => array(
                        'isListLink' => 1,
                        'isList' => 0,
                        'isSqlField' => 1,
                        'isSqlQuery' => 1,
                    ),
                ),
                'category_id' => array(
                    'basic' => array(
                        'title' => 'LBL_FIELD_CATEGORY',
                        'descrip' => '',
                        'order' => 10,
                        'group' => 'LBL_GROUP_BASIC_DATA',
                    ),
                    'form' => array(
                        '_type' => 'select',
                        '_resourceGetter' => array(
                            array('Project_Hepler_Category', 'getTreeResource'),
                            array(
                                'namespace' => 'Default',
                                'module' => 'Category',
                                'controller' => 'Category',
                            ),
                            'product'
                        ),
                        'name' => 'category_id',
                    ),
                    'attr' => array(
                        'isListLink' => 1,
                        'isList' => 1,
                        'isSqlField' => 1,
                        'isSqlQuery' => 1,
                    ),
                    'converter' => array(
                        'attr' => array(
                            array('Project_Hepler_Category', 'convertTreeResource'),
                            array(
                                'namespace' => 'Default',
                                'module' => 'Category',
                                'controller' => 'Category',
                            ),
                            'product'
                        ),
                    ),
                ),
                'category_2' => array(
                    'basic' => array(
                        'title' => 'LBL_FIELD_CATEGORY',
                        'descrip' => '',
                        'order' => 10,
                        'group' => 'LBL_GROUP_BASIC_DATA',
                    ),
                    'form' => array(
                        '_type' => 'hidden',
                        'name' => 'category_2',
                    ),
                    'attr' => array(
                        'isListLink' => 1,
                        'isList' => 0,
                        'isSqlField' => 1,
                        'isSqlQuery' => 1,
                    ),
                ),
                'category_3' => array(
                    'basic' => array(
                        'title' => 'LBL_FIELD_CATEGORY',
                        'descrip' => '',
                        'order' => 10,
                        'group' => 'LBL_GROUP_BASIC_DATA',
                    ),
                    'form' => array(
                        '_type' => 'hidden',
                        'name' => 'category_2',
                    ),
                    'attr' => array(
                        'isListLink' => 1,
                        'isList' => 0,
                        'isSqlField' => 1,
                        'isSqlQuery' => 1,
                    ),
                ),
                'name' => array(
                    'basic' => array(
                        'title' => 'LBL_FIELD_NAME',
                        'descrip' => '',
                        'order' => 5,
                        'group' => 'LBL_GROUP_BASIC_DATA',
                    ),
                    'form' => array(
                        '_type' => 'text',
                        '_typeExt' => '',
                        '_value' => '',
                        'name' => 'name',
                    ),
                    'attr' => array(
                        'isListLink' => 1,
                        'isList' => 1,
                        'isSqlField' => 1,
                        'isSqlQuery' => 1,
                    ),
                    'validator' => array(
                        'rule' => array(
                            'required' => true,
                            'maxlength' => 200,
                        ),
                    ),
                ),
                'is_recommend' => array(
                    'basic' => array(
                        'title' => 'LBL_FIELD_IS_RECOMMEND',
                        'descrip' => '',
                        'order' => 38,
                        'group' => 'LBL_GROUP_BASIC_DATA',
                    ),
                    'form' => array(
                        '_type' => 'select',
                        '_typeExt' => '',
                        '_resource' => $this->getCommonClassList('yes_or_no'),
                        'name' => 'is_recommend',
                    ),
                    'attr' => array(
                        'isListLink' => 1,
                        'isList' => 0,
                        'isSqlField' => 1,
                        'isSqlQuery' => 1,
                    ),
                    'converter' => array(
                        'attr' => array(
                            array($this, 'convertCommonClass'),
                            'yes_or_no'
                        )
                    ),
                ),
                'is_new' => array(
                    'basic' => array(
                        'title' => 'LBL_FIELD_IS_NEW',
                        'descrip' => '',
                        'order' => 40,
                        'group' => 'LBL_GROUP_BASIC_DATA',
                    ),
                    'form' => array(
                        '_type' => 'hidden',
                        '_typeExt' => '',
                        '_resource' => $this->getCommonClassList('yes_or_no'),
                        'name' => 'is_new',
                    ),
                    'attr' => array(
                        'isListLink' => 1,
                        'isList' => 0,
                        'isSqlField' => 1,
                        'isSqlQuery' => 1,
                    ),
                    'converter' => array(
                        'attr' => array(
                            array($this, 'convertCommonClass'),
                            'yes_or_no'
                        )
                    ),
                ),
                'is_hot' => array(
                    'basic' => array(
                        'title' => 'LBL_FIELD_IS_HOT',
                        'descrip' => '',
                        'order' => 40,
                        'group' => 'LBL_GROUP_BASIC_DATA',
                    ),
                    'form' => array(
                        '_type' => 'hidden',
                        '_typeExt' => '',
                        '_resource' => $this->getCommonClassList('yes_or_no'),
                        'name' => 'is_hot',
                    ),
                    'attr' => array(
                        'isListLink' => 1,
                        'isList' => 0,
                        'isSqlField' => 1,
                        'isSqlQuery' => 1,
                    ),
                    'converter' => array(
                        'attr' => array(
                            array($this, 'convertCommonClass'),
                            'yes_or_no'
                        )
                    ),
                ),
                'type_2' => array(
                    'basic' => array(
                        'title' => 'LBL_FIELD_TYPE_2',
                        'descrip' => '',
                        'order' => 42,
                        'group' => 'LBL_GROUP_BASIC_DATA',
                    ),
                    'form' => array(
                        '_type' => 'select',
                        '_typeExt' => '',
                        '_resource' => $this->getCommonClassList('yes_or_no'),
                        'name' => 'type_2',
                        '_value' => '2001002'
                    ),
                    'attr' => array(
                        'isListLink' => 1,
                        'isList' => 1,
                        'isSqlField' => 1,
                        'isSqlQuery' => 1,
                    ),
                    'converter' => array(
                        'attr' => array(
                            array($this, 'convertCommonClass'),
                            'yes_or_no'
                        )
                    ),
                ),
                'type_3' => array(
                    'basic' => array(
                        'title' => 'LBL_FIELD_TYPE_3',
                        'descrip' => '',
                        'order' => 44,
                        'group' => 'LBL_GROUP_BASIC_DATA',
                    ),
                    'form' => array(
                        '_type' => 'select',
                        '_typeExt' => '',
                        '_resource' => $this->getCommonClassList('yes_or_no'),
                        'name' => 'type_3',
                        '_value' => '2001002'
                    ),
                    'attr' => array(
                        'isListLink' => 1,
                        'isList' => 1,
                        'isSqlField' => 1,
                        'isSqlQuery' => 1,
                    ),
                    'converter' => array(
                        'attr' => array(
                            array($this, 'convertCommonClass'),
                            'yes_or_no'
                        )
                    ),
                ),
                'hit' => array(
                    'basic' => array(
                        'title' => 'LBL_FIELD_HIT',
                        'descrip' => '',
                        'order' => 10,
                        'group' => 'LBL_GROUP_BASIC_DATA',
                    ),
                    'form' => array(
                        '_type' => 'hidden',
                        '_typeExt' => '',
                        '_value' => 0,
                        'name' => 'hit',
                    ),
                    'attr' => array(
                        'isListLink' => 1,
                        'isList' => 0,
                        'isSqlField' => 1,
                        'isSqlQuery' => 1,
                    ),
                ),
                'image' => array(
                    'basic' => array(
                        'title' => 'LBL_FIELD_IMAGE',
                        'descrip' => '',
                        'order' => 20,
                        'group' => 'LBL_GROUP_BASIC_DATA',
                    ),
                    'form' => array(
                        '_type' => 'text',
                        '_typeExt' => 'fileTree',
                        '_button' => 'ajaxUpload',
                        '_value' => '',
                        'name' => 'image',
                    ),
                    'attr' => array(
                        'isListLink' => 1,
                        'isList' => 0,
                        'isSqlField' => 1,
                        'isSqlQuery' => 1,
                    ),
                ),
                'image_2' => array(
                    'basic' => array(
                        'title' => 'LBL_FIELD_IMAGE_2',
                        'descrip' => '',
                        'order' => 22,
                        'group' => 'LBL_GROUP_BASIC_DATA',
                    ),
                    'form' => array(
                        '_type' => 'text',
                        '_typeExt' => 'fileTree',
                        '_button' => 'ajaxUpload',
                        '_value' => '',
                        'name' => 'image_2',
                    ),
                    'attr' => array(
                        'isListLink' => 1,
                        'isList' => 0,
                        'isSqlField' => 1,
                        'isSqlQuery' => 1,
                    ),
                ),
                'image_3' => array(
                    'basic' => array(
                        'title' => 'LBL_FIELD_IMAGE_3',
                        'descrip' => '',
                        'order' => 24,
                        'group' => 'LBL_GROUP_BASIC_DATA',
                    ),
                    'form' => array(
                        '_type' => 'text',
                        '_typeExt' => 'fileTree',
                        '_button' => 'ajaxUpload',
                        '_value' => '',
                        'name' => 'image_3',
                    ),
                    'attr' => array(
                        'isListLink' => 1,
                        'isList' => 0,
                        'isSqlField' => 1,
                        'isSqlQuery' => 1,
                    ),
                ),
                'order' => array(
                    'basic' => array(
                        'title' => 'LBL_FIELD_ORDER',
                        'descrip' => '',
                        'order' => 50,
                        'group' => 'LBL_GROUP_BASIC_DATA',
                    ),
                    'form' => array(
                        '_type' => 'text',
                        '_typeExt' => '',
                        '_value' => '',
                        'name' => 'order',
                    ),
                    'attr' => array(
                        'isListLink' => 1,
                        'isList' => 1,
                        'isSqlField' => 1,
                        'isSqlQuery' => 1,
                    ),
                ),
                'date_created' => array(
                    'basic' => array(
                        'title' => 'LBL_FIELD_DATE_CREATED',
                        'descrip' => '',
                        'order' => 420,
                        'group' => 'LBL_GROUP_BASIC_DATA',
                    ),
                    'form' => array(
                        '_type' => 'custom',
                        '_typeExt' => '',
                        '_value' => '',
                        'name' => 'date_created',
                    ),
                    'attr' => array(
                        'isListLink' => 1,
                        'isList' => 1,
                        'isSqlField' => 1,
                        'isSqlQuery' => 1,
                        'isReadonly' => 1,
                    ),
                ),
                'date_modified' => array(
                    'basic' => array(
                        'title' => 'LBL_FIELD_DATE_MODIFIED',
                        'descrip' => '',
                        'order' => 425,
                        'group' => 'LBL_GROUP_BASIC_DATA',
                    ),
                    'form' => array(
                        '_type' => 'custom',
                        '_typeExt' => '',
                        '_value' => '',
                        'name' => 'date_modified',
                    ),
                    'attr' => array(
                        'isListLink' => 1,
                        'isList' => 1,
                        'isSqlField' => 1,
                        'isSqlQuery' => 1,
                    ),
                ),
                'operation' => array(
                    'basic' => array(
                        'title' => 'LBL_FIELD_OPERATION',
                        'order' => 999,
                        'group' => 'LBL_GROUP_BASIC_DATA',
                    ),
                    'form' => array(
                        '_type' => 'custom',
                        '_value' => '',
                        'name' => 'operation',
                    ),
                    'attr' => array(
                        'isListLink' => 0,
                        'isList' => 1,
                        'isSqlField' => 0,
                        'isSqlQuery' => 0,
                        'isView' => 0,
                    ),
                ),
            ),
            // 表之间的联系
            'model' => array(
                array(
                    // 模型类名
                    'name' => 'Default_Product_Model_Detail',
                    'asName' => 'detail',
                    // Metadata 中包含模型字段,表名,关系的定义,
                    'metadata' => 'Default_Product_Metadata_Detail',
                    'type' => 'hasOne',
                    'local' => 'id',
                    'foreign' => 'product_id',
                ),
            ),
            'db' => array(
                'table' => 'product',
                'primaryKey' => 'id',
                'order' => array(
                    array('date_created', 'DESC'),
                ),
                'where' => array(
                    
                ),
            ),
            // 页面显示
            'page' => array(
                'title' => 'LBL_MODULE_PRODUCT',
                'rowNum' => 10,
            ),
            'shortcut' => array(
            )
        );
    }
}
