<?php
/**
 * Metadata
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
 * @since       2010-5-26 22:52:49
 */

class Trex_Article_Metadata_Article extends Trex_Metadata
{
    public function  __construct()
    {
        $this->setCommonMetadata()
             ->parseMetadata(array(
            // 基本属性
            'field' => array(
                'category_id' => array(
                    'basic' => array(
                        'title' => 'LBL_FIELD_CATEGORY_NAME',
                    ),
                    'form' => array(
                        '_type' => 'select',
                        '_resourceGetter' => array(
                            array('Project_Hepler_Category', 'getTreeResource'),
                            array(
                                'namespace' => 'Trex',
                                'module' => 'Category',
                                'controller' => 'Category',
                            ),
                            'article'
                        ),
                    ),
                    'attr' => array(
                        'isListLink' => 1,
                        'isList' => 1,
                        'isDbField' => 1,
                        'isDbQuery' => 1,
                    ),
                ),
                'category_2' => array(
                    'form' => array(
                        '_type' => 'hidden',
                    ),
                    'attr' => array(
                        'isListLink' => 1,
                        'isList' => 0,
                        'isDbField' => 1,
                        'isDbQuery' => 1,
                        'isView' => 0,
                    ),
                ),
                'category_3' => array(
                    'form' => array(
                        '_type' => 'hidden',
                    ),
                    'attr' => array(
                        'isListLink' => 1,
                        'isList' => 0,
                        'isDbField' => 1,
                        'isDbQuery' => 1,
                        'isView' => 0,
                    ),
                ),
                'title' => array(
                    'validator' => array(
                        'required',
                    ),
                ),
                'title_style' => array(
                    'form' => array(
                        '_type' => 'checkbox',
                        '_resourceGetter' => array(
                            array('Project_Helper_CommonClass', 'get'),
                            'font-style',
                        ),
                    ),
                    'attr' => array(
                        'isList' => 0,
                        'isView' => 0,
                    ),
                ),
                'title_color' => array(
                    'form' => array(
                        '_type' => 'radio',
                        '_resourceGetter' => array(
                            array('Project_Helper_CommonClass', 'get'),
                            'font-color',
                        ),
                        '_value' => 'NULL',
                    ),
                    'attr' => array(
                        'isList' => 0,
                        'isView' => 0,
                    ),
                ),
                'short_title' => array(
                    'attr' => array(
                        'isList' => 0,
                    ),
                    'validator' => array(
                        'rule' => array(
                            'maxlength' => 20,
                        ),
                    ),
                ),
                'author' => array(
                    'attr' => array(
                        'isListLink' => 1,
                        'isList' => 0,
                        'isDbField' => 1,
                        'isDbQuery' => 1,
                    ),
                ),
                'thumb' => array(
                    'form' => array(
                        '_widget' => array(
                            'fileTree',
                            'ajaxUpload'
                        ),
                    ),
                    'attr' => array(
                        'isListLink' => 1,
                        'isList' => 0,
                        'isDbField' => 1,
                        'isDbQuery' => 1,
                    ),
                ),
                'jump_to_url' => array(
                    'basic' => array(
                        'group' => 'LBL_GROUP_DETAIL_DATA',
                    ),
                    'form' => array(
                        '_type' => 'text',
                    ),
                    'attr' => array(
                        'isListLink' => 1,
                        'isList' => 0,
                        'isDbField' => 1,
                        'isDbQuery' => 1,
                    ),
                ),
                'hit' => array(
                    'basic' => array(
                        'group' => 'LBL_GROUP_DETAIL_DATA'
                    ),
                    'form' => array(
                        '_value' => 0,
                    ),
                ),
                'order' => array(
                    'basic' => array(
                        'group' => 'LBL_GROUP_DETAIL_DATA'
                    ),
                    'form' => array(
                        '_value' => 0,
                    ),
                ),
                'page_name' => array(
                    'basic' => array(
                        'group' => 'LBL_GROUP_PAGE_DATA',
                    ),
                    'attr' => array(
                        'isList' => 0,
                    ),
                ),
                'template' => array(
                    'basic' => array(
                        'group' => 'LBL_GROUP_PAGE_DATA',
                    ),
                    'attr' => array(
                        'isList' => 0,
                    ),
                ),
                'is_posted' => array(
                    'basic' => array(
                        'group' => 'LBL_GROUP_DETAIL_DATA',
                    ),
                    'form' => array(
                        '_type' => 'select',
                        '_resourceGetter' => array(
                            array('Project_Helper_CommonClass', 'get'),
                            'yes-or-no',
                        ),
                    ),
                    'attr' => array(
                        'isListLink' => 1,
                        'isList' => 0,
                        'isDbField' => 1,
                        'isDbQuery' => 1,
                    ),
                    'converter' => array(
                        'list' => array(
                            array('Project_Helper_CommonClass', 'convert'),
                            'yes-or-no',
                        ),
                    ),
                ),
                'is_index' => array(
                    'basic' => array(
                        'group' => 'LBL_GROUP_DETAIL_DATA',
                    ),
                    'form' => array(
                        '_type' => 'select',
                        '_resourceGetter' => array(
                            array('Project_Helper_CommonClass', 'get'),
                            'yes-or-no',
                        ),
                    ),
                    'attr' => array(
                        'isListLink' => 1,
                        'isList' => 1,
                        'isDbField' => 1,
                        'isDbQuery' => 1,
                    ),
                    'converter' => array(
                        'list' => array(
                            array('Project_Helper_CommonClass', 'convert'),
                            'yes-or-no',
                        ),
                    ),
                ),
                'content_preview' => array(
                    'form' => array(
                        '_type' => 'textarea',
                    ),
                    'attr' => array(
                        'isListLink' => 1,
                        'isList' => 0,
                        'isDbField' => 1,
                        'isDbQuery' => 1,
                    ),
                ),
                /*'date_created' => array(
                    'basic' => array(
                        'title' => 'LBL_FIELD_POST_DATA',
                        'group' => 'LBL_GROUP_DETAIL_DATA'
                    ),
                    'form' => array(
                        '_type' => 'text',
                        '_widget' => 'datepicker',
                        'name' => 'date_created',
                    ),
                    'attr' => array(
                        'isReadonly' => 1,
                    ),
                ),
                'date_modified' => array(
                    'form' => array(
                        '_type' => 'custom',
                        'name' => 'date_modified',
                    ),
                ),*/
            ),
            // 表之间的联系
            'model' => array(
                array(
                    'name' => 'Trex_Article_Model_Detail',
                    'alias' => 'detail',
                    'metadata' => 'Trex_Article_Metadata_Detail',
                    'type' => 'db',
                    'local' => 'id',
                    'foreign' => 'article_id',
                ),
                array(
                    'name' => 'Trex_Category_Model_Category',
                    'alias' => 'category',
                    'metadata' => 'Trex_Category_Metadata_Category',
                    'local' => 'category_id',
                    'foreign' => 'id',
                    'type' => 'view',
                    'fieldMap' => array(
                        'category_id' => 'name',
                    ),
                ),
            ),
            'db' => array(
                'table' => 'article',
                'primaryKey' => 'id',
                'order' => array(
                    array('date_created', 'DESC'),
                ),
                'limit' => 20,
            ),
            // 页面显示
            'page' => array(
                'title' => 'LBL_MODULE_ARTICLE',
            ),
            'shortcut' => array(
            )
        ));
    }
}
