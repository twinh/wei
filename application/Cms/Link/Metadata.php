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
 * @package     Com
 * @subpackage  Link
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-06-17 09:54:00
 */

class Com_Link_Metadata_Link extends Com_Metadata
{
    public function  setMetadata()
    {
        $this->setCommonMetadata();
        $this->merge(array(
            // 基本属性
            'field' => array(
                'category_id' => array(
                    'form' => array(
                        '_type' => 'select',
                        '_resourceGetter' => array(
                            array('Project_Helper_Category', 'getTreeResource'),
                            array(
                                'package' => 'Common',
                                'module' => 'Category',
                                'controller' => 'Category',
                            ),
                            'link'
                        ),
                    ),
                    'attr' => array(
                        'isLink' => 1,
                    ),
                ),
                'name' => array(
                ),
                'url' => array(
                    'form' => array(
                        '_value' => 'http://',
                    ),
                ),
                'target' => array(
                    'form' => array(
                        '_value' => '_self',
                    ),
                ),
                'img_url' => array(
                    'form' => array(
                        '_type' => 'text',
                        '_widget' => array(
                            'fileTree', 'ajaxUpload',
                        ),
                    ),
                    'attr' => array(
                        'isList' => 0,
                    ),
                ),
                'order' => array(
                ),
                'description' => array(
                    'form' => array(
                        '_type' => 'textarea',
                    ),
                    'attr' => array(
                        'isList' => 0,
                    ),
                ),
                'date_created' => array(
                    'form' => array(
                        '_type' => 'custom',
                    ),
                    'attr' => array(
                        'isReadonly' => 1,
                    ),
                ),
                'date_modified' => array(
                    'form' => array(
                        '_type' => 'custom',
                    ),
                ),
            ),
            'group' => array(

            ),
            'model' => array(
                array(
                    'name' => 'Com_Category_Model_Category',
                    'alias' => 'category',
                    'metadata' => 'Com_Category_Metadata_Category',
                    'local' => 'category_id',
                    'foreign' => 'id',
                    'type' => 'view',
                    'fieldMap' => array(
                        'category_id' => 'name',
                    ),
                ),
            ),
            'metadata' => array(

            ),
            'db' => array(
                'table' => 'link',
                'order' => array(
                    array('date_created', 'DESC')
                ),
                'where' => array(
                ),
            ),
            'page' => array(
                'title' => 'LBL_MODULE_LINK',
            )
         ));
    }
}
