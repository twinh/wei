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
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2011-01-05 00:08:51
 */

class Crm_Company_Metadata_Company extends Common_Metadata
{
    public function setMetadata()
    {
        $this->setCommonMetadata();
        $this->parseMetadata(array(
            'field' => array(
                'customer_id' => array(
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
                                    'namespace' => 'Common',
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
                    'basic' => array(
                        'group' => 2,
                    ),
                    'validator' => array(
                        'rule' => array(
                            'required' => true,
                        )
                    ),
                ),
                'industry' => array(
                    'basic' => array(
                        'group' => 2,
                    ),
                    'form' => array(
                        '_type' => 'select',
                        '_resourceGetter' => array(
                            array('Common_Helper_Option', 'get'),
                            'company-industry',
                        ),
                    ),
                    'filterer' => array(
                        'list' => array(
                            array('Common_Helper_Option', 'filter'),
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
                    'basic' => array(
                        'group' => 2,
                    ),
                    'form' => array(
                        '_type' => 'select',
                        '_resourceGetter' => array(
                            array('Common_Helper_Option', 'get'),
                            'company-nature',
                        ),
                    ),
                    'filterer' => array(
                        'list' => array(
                            array('Common_Helper_Option', 'filter'),
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
                    'basic' => array(
                        'group' => 2,
                    ),
                    'form' => array(
                        '_type' => 'select',
                        '_resourceGetter' => array(
                            array('Common_Helper_Option', 'get'),
                            'company-size',
                        ),
                    ),
                    'filterer' => array(
                        'list' => array(
                            array('Common_Helper_Option', 'filter'),
                            'company-size',
                        ),
                        'view' => array(
                            array('Common_Helper_Option', 'filter'),
                            'company-size',
                        )
                    ),
                    'validator' => array(
                        'rule' => array(
                            'required' => true,
                        )
                    ),
                ),
                'contacter' => array(
                    'basic' => array(
                        'group' => 2,
                    ),
                ),
                'phone' => array(
                    'basic' => array(
                        'group' => 2,
                    ),
                ),
                'email' => array(
                    'basic' => array(
                        'group' => 2,
                    ),
                ),
                'address' => array(
                    'basic' => array(
                        'group' => 2,
                    ),
                    'validator' => array(
                        'rule' => array(
                            'required' => true,
                        )
                    ),
                ),
                'description' => array(
                    'basic' => array(
                        'group' => 2,
                    ),
                    'form' => array(
                        '_type' => 'textarea',
                    ),
                    'attr' => array(
                        'isList' => 0
                    ),
                ),
            ),
            'group' => array(),
            'model' => array(
                'member' => array(
                    'name' => 'Common_Member_Model_Member',
                    'alias' => 'member',
                    'metadata' => 'Common_Member_Metadata_Member',
                    'local' => 'member_id',
                    'foreign' => 'id',
                    'type' => 'view',
                    'fieldMap' => array(
                        'member_id' => 'username',
                    ),
                ),
            ),
            'metadata' => array(),
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
    }
}
