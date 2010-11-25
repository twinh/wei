<?php
/**
 * Member
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
 * @subpackage  Member
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-05-13 10:19:22
 */

class Trex_Member_Metadata_Member extends Trex_Metadata
{
    public function  setMetadata()
    {
        $this->setCommonMetadata()
             ->parseMetadata(array(
            // 基本属性
            'field' => array(
                'group_id' => array(
                    'form' => array(
                        '_type' => 'select',
                        '_resourceGetter' => array(
                            array('Project_Helper_Category', 'getTreeResource'),
                            array(
                                'namespace' => 'Trex',
                                'module' => 'Member',
                                'controller' => 'Group',
                            ),
                            NULL,
                            array('id', NULL, 'name')
                        ),
                    ),
                    'attr' => array(
                        'isLink' => 1,
                    ),
                    'validator' => array(
                        'rule' => array(
                            'notNull' => true,
                        ),
                    ),
                ),
                'contact_id' => array(
                    'form' => array(
                        '_type' => 'hidden',
                    ),
                    'attr' => array(
                        'isList' => 0,
                        'isView' => 0,
                    ),
                ),
                'username' => array(
                    'attr' => array(
                        'isReadonly' => 1,
                    ),
                    'validator' => array(
                        'rule' => array(
                            'required' => true,
                            'maxlength' => 40,
                        ),
                    ),
                ),
                'password' => array(
                    'attr' => array(
                        'isList' => 0,
                        'isReadonly' => 1,
                        'isView' => 0,
                    ),
                    'converter' => array(
                        'db' => array(
                            'md5'
                        )
                    ),
                    'validator' => array(
                        'rule' => array(
                            'required' => true,
                            'maxlength' => 40,
                        ),
                    ),
                ),
                'email' => array(
                    'validator' => array(
                        'rule' => array(
                            'required' => true,
                            'email' => true,
                            'maxlength' => 256,
                        ),
                    ),
                ),
                'reg_ip' => array(
                    'attr' => array(
                        'isList' => 0,
                    ),
                ),
                'theme' => array(
                    'attr' => array(
                        'isList' => 0,
                    ),
                ),
                'language' => array(
                    'attr' => array(
                        'isList' => 0,
                    ),
                ),
                'money' => array(

                ),
            ),
            'group' => array(

            ),
            // 表之间的联系
            'model' => array(
                'contact' => array(
                    'name' => 'Trex_Contact_Model_Contact',
                    'alias' => 'contact',
                    'metadata' => 'Trex_Contact_Metadata_Contact',
                    'local' => 'contact_id',
                    'foreign' => 'id',
                ),
                /*array(
                    // 模型类名
                    'name' => 'Trex_Member_Model_Company',
                    'alias' => 'company',
                    // Metadata 中包含模型字段,表名,关系的定义,
                    'metadata' => 'Trex_Member_Metadata_Company',
                    'type' => 'hasOne',
                    'local' => 'id',
                    'foreign' => 'member_id',
                ),*/
                array(
                    'name' => 'Trex_Member_Model_Group',
                    'alias' => 'group',
                    'metadata' => 'Trex_Member_Metadata_Group',
                    'local' => 'group_id',
                    'foreign' => 'id',
                    'type' => 'view',
                    'fieldMap' => array(
                        'group_id' => 'name',
                    ),
                ),
            ),
            'metadata' => array(

            ),
            'db' => array(
                'table' => 'member',
                'nameKey' => array(
                    'username',
                ),
            ),
            // 页面显示
            'page' => array(
                'title' => 'LBL_MODULE_MEMBER',
            ),
        ));
    }

    public function convertListOperation($value, $name, $data, $copyData)
    {
        $primaryKey = $this->db['primaryKey'];
        $url = Qwin::run('-url');
        $lang = Qwin::run('-lang');
        $set = $this->getSetFromClass();
        $html = Qwin_Helper_Html::jQueryButton($url->createUrl($set, array('action' => 'EditPassword', $primaryKey => $copyData[$primaryKey])), $lang->t('LBL_ACTION_EDIT_PASSWORD'), 'ui-icon-key')
              . parent::convertListOperation($value, $name, $data, $copyData);
        return $html;
    }

    public function validateUsername($value, $name)
    {
        $set = Qwin::run('-ini')->getSet();
        if ('Edit' == $set['action']) {
            return true;
        }
        $set = $this->getSetFromClass();
        $lang = Qwin::run('-lang');
        $result = $this->isUsernameExists($value);

        if(true === $result)
        {
            return new Qwin_Validator_Result(false, $name, 'MSG_USERNAME_EXISTS');
        }
        return true;
    }

    public function isUsernameExists($username)
    {
        $set = $this->getSetFromClass();
        $query = $this->metaHelper->getQueryBySet($set);
        $result = $query->where('username = ?', $username)
            ->fetchOne();
        if(false != $result)
        {
            $result = true;
        }
        return $result;
    }
}
