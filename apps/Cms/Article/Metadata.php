<?php
/**
 * Meta
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
 * @subpackage  Article
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-5-26 22:52:49
 */

class Cms_Article_Meta extends Com_Meta
{
    public function setMeta()
    {
        $this->setCommonMeta()
             ->merge(array(
            // 基本属性
            'field' => array(
                'category_id' => array(
                    'basic' => array(
                        'title' => 'FLD_CATEGORY_NAME',
                    ),
                    'form' => array(
                        '_type' => 'select',
                        '_resourceGetter' => array(
                            array('Com_Category_Widget', 'get'),
                            array(
                                'com/category',
                                null,
                                array('id', 'category_id', 'title'),
                            ),
                        ),
                    ),
                    'attr' => array(
                        'isList' => 1,
                        'isLink' => 1,
                    ),
                ),
                'category_2' => array(
                    'form' => array(
                        '_type' => 'hidden',
                    ),
                    'attr' => array(
                        'isLink' => 1,
                        'isView' => 0,
                    ),
                ),
                'category_3' => array(
                    'form' => array(
                        '_type' => 'hidden',
                    ),
                    'attr' => array(
                        'isLink' => 1,
                        'isList' => 0,
                        'isView' => 0,
                    ),
                ),
                'title' => array(
                    'basic' => array(
                        'layout' => 2,
                    ),
                    'attr' => array(
                        'isList' => 1,
                    ),
                    'validator' => array(
                        'rule' => array(
                            'required' => true,
                        ),
                    ),
                    'list' => array(
                        'width' => '300',
                    ),
                ),
                'title_style' => array(
                    'form' => array(
                        '_type' => 'checkbox',
                        '_resourceGetter' => array(
                            array('Ide_Option_Widget', 'get'),
                            'font-style',
                        ),
                    ),
                    'attr' => array(
                        'isList' => 0,
                        'isView' => 0,
                    ),
                    'db' => array(
                        'type' => 'array',
                    ),
                ),
                'title_color' => array(
                    'form' => array(
                        '_type' => 'radio',
                        '_resourceGetter' => array(
                            array('Ide_Option_Widget', 'get'),
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
                ),
                'author' => array(
                    'attr' => array(
                        'isLink' => 1,
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
                        'isLink' => 1,
                        'isDbField' => 1,
                        'isDbQuery' => 1,
                    ),
                ),
                'jump_to_url' => array(
                    'basic' => array(
                        'group' => 1,
                    ),
                    'form' => array(
                        '_type' => 'text',
                    ),
                    'attr' => array(
                        'isLink' => 1,
                        'isDbField' => 1,
                        'isDbQuery' => 1,
                    ),
                ),
                'hit' => array(
                    'basic' => array(
                        'group' => 1
                    ),
                    'form' => array(
                        '_value' => '0',
                    ),
                    'attr' => array(
                        'isList' => 1,
                    ),
                ),
                'order' => array(
                    'basic' => array(
                        'group' => 1
                    ),
                    'form' => array(
                        '_value' => 0,
                    ),
                    'attr' => array(
                        'isList' => 1,
                    ),
                ),
                'page_name' => array(
                    'basic' => array(
                        'group' => 2,
                    ),
                ),
                'template' => array(
                    'basic' => array(
                        'group' => 2,
                    ),
                ),
                'is_posted' => array(
                    'basic' => array(
                        'group' => 1,
                    ),
                    'form' => array(
                        '_type' => 'select',
                        '_resourceGetter' => array(
                            array('Ide_Option_Widget', 'get'),
                            'yes-or-no',
                        ),
                    ),
                    'attr' => array(
                        'isLink' => 1,
                        'isList' => 0,
                        'isDbField' => 1,
                        'isDbQuery' => 1,
                    ),
                    'sanitiser' => array(
                        'list' => array(
                            array('Ide_Option_Widget', 'sanitise'),
                            'yes-or-no',
                        ),
                    ),
                ),
                'is_index' => array(
                    'basic' => array(
                        'group' => 1,
                    ),
                    'form' => array(
                        '_type' => 'select',
                        '_resourceGetter' => array(
                            array('Ide_Option_Widget', 'get'),
                            'yes-or-no',
                        ),
                    ),
                    'attr' => array(
                        'isLink' => 1,
                        'isList' => 0,
                        'isDbField' => 1,
                        'isDbQuery' => 1,
                    ),
                    'sanitiser' => array(
                        'list' => array(
                            array('Ide_Option_Widget', 'sanitise'),
                            'yes-or-no',
                        ),
                    ),
                ),
                'content_preview' => array(
                    'basic' => array(
                        'layout' => 2,
                    ),
                    'form' => array(
                        '_type' => 'textarea',
                    ),
                    'attr' => array(
                        'isLink' => 1,
                        'isList' => 0,
                        'isDbField' => 1,
                        'isDbQuery' => 1,
                    ),
                ),
                /*'date_created' => array(
                    'basic' => array(
                        'title' => 'FLD_POST_DATA',
                        'group' => 1
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
            'group' => array(
                0 => 'LBL_GROUP_BASIC_DATA',
                1 => 'LBL_GROUP_DETAIL_DATA',
                2 => 'LBL_GROUP_PAGE_DATA',
                3 => 'LBL_GROUP_META_DATA',
            ),
            'layout' => array(

            ),
            'model' => array(
                /*'detail' => array(
                    'alias' => 'detail',
                    'type' => 'db',
                    'local' => 'id',
                    'foreign' => 'article_id',
                    'set' => array(
                        'package' => 'Common',
                        'module' => 'Article',
                        'controller' => 'Detail',
                    ),
                ),
                'category' => array(
                    'name' => 'Com_Category_Model_Category',
                    'alias' => 'category',
                    'meta' => 'Com_Category_Meta_Category',
                    'local' => 'category_id',
                    'foreign' => 'id',
                    'type' => 'view',
                    'fieldMap' => array(
                        'category_id' => 'name',
                    ),
                ),*/
            ),
            'meta' => array(

            ),
            'db' => array(
                'table' => 'article',
                'nameField' => array(
                    'title',
                ),
                'order' => array(
                    array('date_created', 'DESC'),
                ),
                'limit' => 20,
            ),
            // 页面显示
            'page' => array(
                'title' => 'LBL_MODULE_ARTICLE',
                'icon' => 'document',
            ),
            'shortcut' => array(
            )
        ));
    }

    public function sanitiseAddCategoryId($val, $name, $data, $copyData)
    {
        if(isset($_GET['sign']))
        {
            $this->__meta['field']['category_id']['form']['_resourceGetter'] = array(
                array('Project_Helper_Category', 'getTreeResource'),
                array(
                    'package' => 'Default',
                    'module' => 'Category',
                    'controller' => 'Category',
                ),
                $_GET['sign']
            );
        }
        return $val;
    }

    public function sanitiseAddCategory2()
    {
        if(isset($_GET['sign']))
        {
            return $_GET['sign'];
        }
    }

    /*public function sanitiseEditCategoryId($val, $name, $data)
    {
        // 专题
        if(NULL != $data['category_2'])
        {
            $this->__meta['field']['category_id']['form']['_resourceGetter'] = array(
                array('Project_Helper_Category', 'getTreeResource'),
                array(
                    'package' => 'Default',
                    'module' => 'Category',
                    'controller' => 'Category',
                ),
                $data['category_2']
            );
        }
        return $val;
    }*/

    public function sanitiseListTitle($value, $name, $data, $copyData)
    {
        return Qwin_Util_Html::decorateWord($value, $copyData['title_style'], $copyData['title_color']);
    }

    public function sanitiseViewTitle($value, $name, $data, $copyData)
    {
        return Qwin_Util_Html::decorateWord($value, $copyData['title_style'], $copyData['title_color']);
    }

    public function sanitiseEditTitleStyle($value, $name, $data, $copyData)
    {
        if (is_array($value)) {
            return explode('|', $value[0]);
        }
        return $value;
    }

    public function sanitiseEditTitleColor($value, $name, $data, $copyData)
    {
        null == $value && $value = 'NULL';
        return $value;
    }

    /**
     *
     * @todo 检查
     */
    public function sanitiseDbTitleStyle($value, $name, $data, $copyData)
    {
        return implode('|', (array)$value);
    }

    public function sanitiseDbTitleColor($value, $name, $data, $copyData)
    {
        'NULL' == $value && $value = null;
        return $value;
    }
}
