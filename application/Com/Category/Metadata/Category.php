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
 * @package     Com
 * @subpackage  Category
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-07-12 16:52:28
 */

class Com_Category_Metadata_Category extends Com_Metadata
{
    public function  setMetadata()
    {
        parent::setCommonMetadata();
        $this->merge(array(
            'field' => array(
                'parent_id' => array(
                    'basic' => array(
                        'title' => 'FLD_PARENT_NAME',
                    ),
                    'form' => array(
                        '_type' => 'select',
                        '_resourceGetter' => array(
                            array('Com_Category_Controller_Category', 'getCategoryResource'),
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
                    'attr' => array(
                        'isList' => 1,
                    ),
                    'validator' => array(
                        'rule' => array(
                            'required' => true,
                            'maxlength' => 40,
                        ),
                    ),
                ),
                'image' => array(
                    'form' => array(
                        '_widget' => array(
                            'fileTree', 'ajaxUpload',
                        ),
                        'name' => 'image',
                    ),
                ),
                'image_2' => array(
                    'form' => array(
                        '_widget' => array(
                            'fileTree', 'ajaxUpload',
                        ),
                        'name' => 'image_2',
                    ),
                ),
                'order' => array(
                    'form' => array(
                        'name' => 'order',
                    ),
                    'attr' => array(
                        'isList' => 1,
                    ),
                ),
                'to_url' => array(
                    'form' => array(
                        'name' => 'to_url',
                    ),
                ),
                'description' => array(
                    'form' => array(
                        '_type' => 'textarea',
                        'name' => 'description',
                    ),
                ),
            ),
            'group' => array(

            ),
            'model' => array(

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

    public function sanitiseListOperation($value, $name, $data, $copyData)
    {
        $primaryKey = $this->db['primaryKey'];
        $url = Qwin::call('-url');
        $lang = Qwin::call('-lang');
        $set = $this->getAsc();
        $link = $url->url($set, array('action' => 'Add', '_data[parent_id]' => $data[$primaryKey]));
        $html = Qwin_Util_Html::jQueryButton($link, $lang->t('ACT_ADD_SUBCATEGORY'), 'ui-icon-plusthick')
              . parent::sanitiseListOperation($value, $name, $data, $copyData);
        return $html;
    }

    public function sanitiseListName($val, $name, $data, $copyData)
    {
        if(NULL != $copyData['parent_id'])
        {
            // 缓存Tree对象
            if(!isset($this->treeObj))
            {
                $this->treeObj = Qwin::call('Qwin_Tree');
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

    public function sanitiseDbParentId($val, $name, $data)
    {
        '0' == $val && $val = 'NULL';
        return $val;
    }
}
