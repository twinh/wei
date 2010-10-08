<?php
/**
 * Enus
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
 * @subpackage  Job
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-07-18 10:32:28
 */

class Trex_Job_Metadata_Job extends Trex_Metadata
{
    public function  __construct()
    {
        $this->setCommonMetadata();
        $this->parseMetadata(array(
            'field' => array(
                'related_id' => array(
                    'basic' => array(
                        'title' => 'LBL_FIELD_COMPANY_NAME',
                    ),
                    'form' => array(
                        '_type' => 'text',
                        '_widgetDetail' => array(
                            array(
                                array('Qwin_Widget_JQuery_PopupGrid', 'render'),
                                'LBL_MODULE_COMPANY',
                                array(
                                    'namespace' => 'Trex',
                                    'module' => 'Company',
                                    'controller' => 'Company',
                                    'action' => 'Popup',
                                    '_list' => 'id,name',
                                ),
                                array(
                                    'name',
                                    'id'
                                ),
                            ),
                        ),
                    ),
                    'attr' => array(
                        'isLink' => 1,
                    ),
                ),
                'type' => array(
                    'form' => array(
                        '_type' => 'select',
                        '_resourceGetter' => array(
                            array('Project_Helper_CommonClass', 'get'),
                            'job_type',
                        ),
                    ),
                    'attr' => array(
                        'isLink' => true,
                    ),
                    'converter' => array(
                        'list' => array(
                            array('Project_Helper_CommonClass', 'convert'),
                            'job_type',
                        ),
                        'view' => 'list'
                    ),
                    'attr' => array(
                        'isLink' => 1,
                    ),
                ),
                'work_type' => array(
                    'form' => array(
                        '_type' => 'select',
                        '_resourceGetter' => array(
                            array('Project_Helper_CommonClass', 'get'),
                            'work_type',
                        ),
                    ),
                    'attr' => array(
                        'isLink' => true,
                    ),
                    'converter' => array(
                        'list' => array(
                            array('Project_Helper_CommonClass', 'convert'),
                            'work_type',
                        ),
                        'view' => 'list'
                    ),
                    'attr' => array(
                        'isLink' => 1,
                    ),
                ),
                'title' => array(
                    'validator' => array(
                        'rule' => array(
                            'required' => true,
                            'byteRangeLength' => array(4, 16),
                        ),
                    ),
                ),
                'number' => array(
                    'form' => array(
                        'maxlength' => 3
                    ),
                    'validator' => array(
                        'rule' => array(
                            'nonnegativeInteger' => true,
                        ),
                    ),
                ),
                'education' => array(
                    'form' => array(
                        '_type' => 'select',
                        '_resourceGetter' => array(
                            array('Project_Helper_CommonClass', 'get'),
                            'job_education',
                        ),
                    ),
                    'attr' => array(
                        'isLink' => true,
                    ),
                    'converter' => array(
                        'list' => array(
                            array('Project_Helper_CommonClass', 'convert'),
                            'job_education',
                        ),
                        'view' => 'list'
                    ),
                    'attr' => array(
                        'isLink' => 1,
                    ),
                ),
                'work_seniority' => array(
                    'form' => array(
                        '_type' => 'select',
                        '_resourceGetter' => array(
                            array('Project_Helper_CommonClass', 'get'),
                            'work_seniority',
                        ),
                    ),
                    'attr' => array(
                        'isLink' => true,
                    ),
                    'converter' => array(
                        'list' => array(
                            array('Project_Helper_CommonClass', 'convert'),
                            'work_seniority',
                        ),
                        'view' => 'list'
                    ),
                ),
                'salary_from' => array(
                    'attr' => array(
                        'isList' => false,
                        'isView' => false,
                    ),
                    'validator' => array(
                        'rule' => array(
                            'nonnegativeInteger' => true,
                        ),
                    ),
                ),
                'salary_to' => array(
                    'attr' => array(
                        'isList' => false,
                        'isView' => false,
                    ),
                    'validator' => array(
                        'rule' => array(
                            'nonnegativeInteger' => true,
                        ),
                    ),
                ),
                'salary' => array(
                    'form' => array(
                        '_type' => 'custom',
                    ),
                    'attr' => array(
                        'isDbField' => false,
                    ),
                ),
                'working_place' => array(
                    'attr' => array(
                        'isList' => false,
                    ),
                ),
                'description' => array(
                    'form' => array(
                        '_type' => 'textarea',
                    ),
                    'attr' => array(
                        'isList' => false,
                    ),
                ),
                'contacter' => array(
                    'validator' => array(
                        'rule' => array(
                            'required' => true,
                            'byteRangeLength' => array(2, 16),
                        ),
                    ),
                ),
                'phone' => array(
                    'validator' => array(
                        'rule' => array(
                            'required' => true,
                            'byteRangeLength' => array(6, 16),
                        ),
                    ),
                ),
                'email' => array(
                    'validator' => array(
                        'rule' => array(
                            'required' => true,
                            'email' => true,
                        ),
                    ),
                ),
            ),
            'model' => array(
                'company' => array(
                    'name' => 'Trex_Company_Model_Company',
                    'alias' => 'company',
                    'metadata' => 'Trex_Company_Metadata_Company',
                    'local' => 'related_id',
                    'foreign' => 'id',
                    'type' => 'view',
                    'fieldMap' => array(
                        'related_id' => 'name',
                    ),
                ),
            ),
            'db' => array(
                'table' => 'job',
                'order' => array(
                    array('date_created', 'DESC'),
                ),
                'where' => array(

                ),
            ),
            'page' => array(
                'title' => 'LBL_MODULE_JOB',
            ),
        ));
    }
}
