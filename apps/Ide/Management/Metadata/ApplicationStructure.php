<?php
/**
 * ApplicationStructure
 *
 * Copyright (c) 2008-2012 Twin Huang. All rights reserved.
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
 * @package     QWIN_PATH
 * @subpackage  
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-09-11 21:41:36
 */

class Com_Management_Meta_ApplicationStructure extends Com_Meta
{
    public function __construct()
    {
        $this->setIdMeta();
        $this->setOperationMeta();
        $this->merge(array(
            'field' => array(
                'package' => array(
                    'validator' => array(
                        'rule' => array(
                            'required' => true,
                            'pathName' => true,
                        ),
                    ),
                ),
            ),
            'model' => array(

            ),
            'db' => array(
                'table' => 'management_application_structure',
                'order' => array(
                    array('date_created', 'DESC'),
                ),
            ),
            'page' => array(
                'title' => 'LBL_MODULE_APPLICATION_STRUCTURE',
                'icon' => 'gear',
            ),
        ));
    }
}
