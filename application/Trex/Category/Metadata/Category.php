<?php
/**
 * Category
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
 * @subpackage  Category
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-07-12 16:52:28
 */

class Trex_Category_Metadata_Category extends Trex_Metadata
{
    public function  setMetadata()
    {
        parent::setCommonMetadata();
        $this->parseMetadata(array(
            'field' => array(
                'parent_id' => array(
                    'basic' => array(
                        'title' => 'LBL_FIELD_PARENT_NAME',
                    ),
                    'form' => array(
                        '_type' => 'select',
                        '_resourceGetter' => array(
                            array('Trex_Category_Controller_Category', 'getCategoryResource'),
                        ),
                        'name' => 'parent_id',
                    ),
                    'attr' => array(
                        'isList' => 0,
                    ),
                ),
                'name' => array(
                    'form' => array(
                        'name' => 'name',
                    ),
                    'validator' => array(
                        'required',
                        'maxlength,40',
                    ),
                ),
                'image' => array(
                    'form' => array(
                        '_widget' => array(
                            'fileTree', 'ajaxUpload',
                        ),
                        'name' => 'image',
                    ),
                    'attr' => array(
                        'isList' => 0,
                    ),
                ),
                'image_2' => array(
                    'form' => array(
                        '_widget' => array(
                            'fileTree', 'ajaxUpload',
                        ),
                        'name' => 'image_2',
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
                'to_url' => array(
                    'form' => array(
                        'name' => 'to_url',
                    ),
                    'attr' => array(
                        'isList' => 0,
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
            ),
            'group' => array(

            ),
            'model' => array(

            ),
            'metadata' => array(

            ),
            'db' => array(
                'table' => 'category',
                'order' => array(
                    array('order', 'ASC'),
                    array('date_created', 'DESC'),
                ),
                'limit' => 100,
            ),
            'page' => array(
                'title' => 'LBL_MODULE_CATEGORY',
            ),
        ));
    }

    public function convertListOperation($value, $name, $data, $copyData)
    {
        $primaryKey = $this->db['primaryKey'];
        $url = Qwin::run('-url');
        $lang = Qwin::run('-lang');
        $set = $this->getSetFromClass();
        $link = $url->createUrl($set, array('action' => 'Add', '_data[parent_id]' => $data[$primaryKey]));
        $html = Qwin_Helper_Html::jQueryButton($link, $lang->t('LBL_ACTION_ADD_SUBCATEGORY'), 'ui-icon-plusthick')
              . parent::convertListOperation($value, $name, $data, $copyData);
        return $html;
    }

    public function convertListName($val, $name, $data, $copyData)
    {
        if(NULL != $copyData['parent_id'])
        {
            // 缓存Tree对象
            if(!isset($this->treeObj))
            {
                $this->treeObj = Qwin::run('Qwin_Tree');
            }
            $layer = $this->treeObj->getLayer($data['id']);
            // 只有一层
            if(0 >= $layer)
            {
                return $val;
            } else {
                return str_repeat('┃', $layer - 1) . '┣' . $val;
            }
        }
        return $val;
    }

    public function convertDbParentId($val, $name, $data)
    {
        '0' == $val && $val = 'NULL';
        return $val;
    }
}
