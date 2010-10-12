<?php
/**
 * Company
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
 * @subpackage  Company
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-09-18 23:15:46
 */

class Trex_Company_Metadata_Company extends Trex_Metadata
{
    public function  __construct()
    {
        $this->setCommonMetadata();
        $this->parseMetadata(array(
            'field' => array(
                'member_id' => array(
                    'basic' => array(
                        'title' => 'LBL_FIELD_MEMBER_NAME',
                    ),
                    'form' => array(
                        '_type' => 'text',
                        '_widgetDetail' => array(
                            array(
                                array('Qwin_Widget_JQuery_PopupGrid', 'render'),
                                'LBL_MODULE_MEMBER',
                                array(
                                    'namespace' => 'Trex',
                                    'module' => 'Member',
                                    'controller' => 'Member',
                                    'action' => 'Popup',
                                    'listName' => 'id,group_id,username,email',
                                ),
                                array(
                                    'username',
                                    'id'
                                ),
                            ),
                        ),
                    ),
                    'attr' => array(
                        'isLink' => 1,
                    ),
                    'validator' => array(
                        'rule' => array(
                            'required' => true,
                            'notNull' => true,
                        ),
                    ),
                ),
                'name' => array(
                    'validator' => array(
                        'rule' => array(
                            'required' => true,
                        )
                    ),
                ),
                'industry' => array(
                    'form' => array(
                        '_type' => 'select',
                        '_resourceGetter' => array(
                            array('Project_Helper_CommonClass', 'get'),
                            'company-industry',
                        ),
                    ),
                    'converter' => array(
                        'list' => array(
                            array('Project_Helper_CommonClass', 'convert'),
                            'company-industry',
                        ),
                        'view' => 'list',
                    ),
                    'validator' => array(
                        'rule' => array(
                            'required' => true,
                        )
                    ),
                ),
                'nature' => array(
                    'form' => array(
                        '_type' => 'select',
                        '_resourceGetter' => array(
                            array('Project_Helper_CommonClass', 'get'),
                            'company-nature',
                        ),
                    ),
                    'converter' => array(
                        'list' => array(
                            array('Project_Helper_CommonClass', 'convert'),
                            'company-nature',
                        ),
                        'view' => 'list'
                    ),
                    'validator' => array(
                        'rule' => array(
                            'required' => true,
                        )
                    ),
                ),
                'size' => array(
                    'form' => array(
                        '_type' => 'select',
                        '_resourceGetter' => array(
                            array('Project_Helper_CommonClass', 'get'),
                            'company-size',
                        ),
                    ),
                    'converter' => array(
                        'list' => array(
                            array('Project_Helper_CommonClass', 'convert'),
                            'company-size',
                        ),
                        'view' => array(
                            array('Project_Helper_CommonClass', 'convert'),
                            'company-size',
                        )
                    ),
                    'validator' => array(
                        'rule' => array(
                            'required' => true,
                        )
                    ),
                ),
                'address' => array(
                    'validator' => array(
                        'rule' => array(
                            'required' => true,
                        )
                    ),
                ),
                'description' => array(
                    'form' => array(
                        '_type' => 'textarea',
                    ),
                    'attr' => array(
                        'isList' => 0
                    ),
                ),
            ),
            'model' => array(
                'member' => array(
                    'name' => 'Trex_Member_Model_Member',
                    'alias' => 'member',
                    'metadata' => 'Trex_Member_Metadata_Member',
                    'local' => 'member_id',
                    'foreign' => 'id',
                    'type' => 'view',
                    'fieldMap' => array(
                        'member_id' => 'username',
                    ),
                ),
            ),
            'db' => array(
                'table' => 'company',
                'order' => array(
                    array('date_created', 'DESC'),
                ),
            ),
            'page' => array(
                'title' => 'LBL_MODULE_COMPANY',
            ),
        ));
        $this->field->set('operation.list.width', 180);
    }
}