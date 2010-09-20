<?php
/**
 * Feature
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
 * @subpackage  Project
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-07-09 10:38:41
 */

class Trex_Project_Metadata_Feature extends Trex_Metadata
{
    public function __construct()
    {
        $this->setCommonMetadata();
        $this->parseMetadata(array(
            'field' => array(
                'name' => array(
                    'form' => array(
                        'name' => 'name',
                    ),
                ),
                'from' => array(
                    'form' => array(
                        'name' => 'from',
                    ),
                    'attr' => array(
                        'isLink' => 1,
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
            'model' => array(

            ),
            'db' => array(
                'table' => 'project_feature',
                'order' => array(
                    array('date_created', 'DESC')
                ),
            ),
            'page' => array(
                'title' => 'LBL_MODULE_PROJECT_FEATURE',
            ),
        ));
    }
}
