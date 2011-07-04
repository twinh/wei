<?php
/**
 * AdminMenu
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
 * @subpackage  AdminMenu
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @since       2010-05-25 08:00:36
 */

class Member_Menu_Meta extends Meta_Widget
{
    public function setMeta()
    {
        $this->setCommonMeta();
        $this->merge(array(
            // 基本属性
            'field' => array(
                'category_id' => array(
                    'form' => array(
                        '_type' => 'select',
                        '_resourceGetter' => array(
                            array('Com_Category_Widget', 'get'),
                            array(
                                'com/member/menu',
                                null,
                                array('id', 'category_id', 'title'),
                            ),
                        ),
                    ),
                    'attr' => array(
                        'isList' => 1,
                        'isLink' => 1,
                    ),
                    'sanitiser' => array(
                        'list' => array(
                            array('Com_Category_Widget', 'sanitise'),
                            array(
                                'com/member/menu',
                                null,
                                array('id', 'category_id', 'title'),
                            ),
                        ),
                        'view' => 'list',
                    ),
                ),
                'title' => array(
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
                'url' => array(
                    'attr' => array(
                        'isLink' => 0,
                        'isList' => 1,
                        'isDbField' => 1,
                        'isDbQuery' => 1,
                    ),
                    'validator' => array(
                        'rule' => array(
                            'required' => true,
                            'maxlength' => 256,
                        ),
                    ),
                ),
                'target' => array(
                    'form' => array(
                        '_value' => '_self',
                    ),
                    'attr' => array(
                        'isLink' => 1,
                        'isList' => 1,
                    ),
                    'validator' => array(
                        'rule' => array(
                            'required' => true,
                            'maxlength' => 16,
                        ),
                    ),
                ),
                'order' => array(
                    'attr' => array(
                        'isList' => 1,
                        'isLink' => 1,
                    ),
                ),
            ),
            'group' => array(
            ),
            'model' => array(
                'creator' => array(
                    'module' => 'Com/Member',
                    'alias' => 'creator',
                    'local' => 'created_by',
                    'type' => 'view',
                    'fieldMap' => array(
                        'created_by' => 'username',
                    ),
                ),
                'modifier' => array(
                    'module' => 'Com/Member',
                    'alias' => 'modifier',
                    'local' => 'modified_by',
                    'type' => 'view',
                    'fieldMap' => array(
                        'modified_by' => 'username',
                    ),
                ),
            ),
            'db' => array(
                'table' => 'member_menu',
                'order' => array(
                    array('order', 'DESC')
                ),
                'limit' => 20,
                'nameField' => array(
                    'title'
                ),
            ),
            'page' => array(
                'title' => 'LBL_MODULE_ADMIN_MENU',
            ),
        ));
    }
}
