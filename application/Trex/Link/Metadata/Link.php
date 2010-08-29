<?php
/**
 * Link
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
 * @subpackage  Link
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-06-17 09:54:00
 */

class Trex_Link_Metadata_Link extends Trex_Metadata
{
    public function  __construct()
    {
        $this->setCommonMetadata();
        $this->parseMetadata(array(
            // 基本属性
            'field' => array(
                'category_id' => array(
                    'form' => array(
                        '_type' => 'select',
                        '_value' => '',
                        'name' => 'category_id',
                        '_resourceGetter' => array(
                            array('Project_Hepler_Category', 'getTreeResource'),
                            array(
                                'namespace' => 'Trex',
                                'module' => 'Category',
                                'controller' => 'Category',
                            ),
                            'link'
                        ),
                    ),
                    'attr' => array(
                        'isListLink' => 1,
                    ),
                ),
                'name' => array(
                    'form' => array(
                        'name' => 'name',
                    ),
                ),
                'url' => array(
                    'form' => array(
                        '_value' => 'http://',
                        'name' => 'url',
                    ),
                ),
                'target' => array(
                    'form' => array(
                        '_value' => '_self',
                        'name' => 'target',
                    ),
                ),
                'img_url' => array(
                    'form' => array(
                        '_type' => 'text',
                        '_widget' => array(
                            'fileTree', 'ajaxUpload',
                        ),
                        'name' => 'img_url',
                    ),
                    'attr' => array(
                        'isList' => 0,
                    ),
                ),
                'order' => array(
                    'form' => array(
                        'name' => 'order',
                    ),
                ),
                'description' => array(
                    'form' => array(
                        '_type' => 'textarea',
                        'name' => 'description',
                    ),
                    'attr' => array(
                        'isList' => 0,
                    ),
                ),
                'date_created' => array(
                    'form' => array(
                        '_type' => 'custom',
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
                ),
            ),
            // 表之间的联系
            'model' => array(
                array(
                    'name' => 'Trex_Category_Model_Category',
                    'asName' => 'category',
                    'metadata' => 'Trex_Category_Metadata_Category',
                    'local' => 'category_id',
                    'foreign' => 'id',
                    'aim' => 'view',
                    'viewMap' => array(
                        'category_id' => 'name',
                    ),
                ),
            ),
            'db' => array(
                'table' => 'link',
                'order' => array(
                    array('date_created', 'DESC')
                )
            ),
            // 页面显示
            'page' => array(
                'title' => 'LBL_MODULE_LINK',
            )
         ));
    }
}
