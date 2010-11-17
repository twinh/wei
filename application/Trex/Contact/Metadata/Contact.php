<?php
/**
 * Contact
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
 * @subpackage  Contact
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-09-01 11:10:20
 */

class Trex_Contact_Metadata_Contact extends Trex_Metadata
{
    public function __construct()
    {
        $this->setCommonMetadata();
        $this->parseMetadata(array(
            'field' => array(
                'first_name' => array(

                ),
                'last_name' => array(

                ),
                'nickname' => array(

                ),
                'photo' => array(
                    'form' => array(
                        '_widget' => array(
                            'fileTree',
                            'ajaxUpload',
                        ),
                    ),
                    'attr' => array(
                        'isList' => 0,
                    ),
                ),
                'sex' => array(
                    'form' => array(
                        '_type' => 'select',
                        '_resourceGetter' => array(
                            array('Project_Helper_CommonClass', 'get'),
                            'sex',
                        ),
                    ),
                    'converter' => array(
                        'list' => array(
                            array('Project_Helper_CommonClass', 'convert'),
                            'sex',
                        ),
                        'view' => 'list',
                    ),
                    'attr' => array(
                        'isLink' => 1,
                    ),
                ),
                'related_module' => array(
                    'form' => array(
                        '_type' => 'hidden',
                    ),
                    'attr' => array(
                        'isList' => 0,
                    ),
                ),
                'birthday' => array(
                    'form' => array(
                        '_widget' => 'datepicker',
                    )
                ),
                'email' => array(

                ),
                'telephone' => array(
                    'attr' => array(
                        'isList' => 0,
                    ),
                ),
                'mobile' => array(
                    'attr' => array(
                        'isList' => 0,
                    ),
                ),
                'homepage' => array(
                    'attr' => array(
                        'isList' => 0,
                    ),
                ),
                'address' => array(
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
                'table' => 'contact',
                'order' => array(
                    array('date_created', 'ASC'),
                )
            ),
            'page' => array(
                'title' => 'LBL_MODULE_CONTACT',
            ),
        ));
        $this
            ->field
            ->set('date_created.attr.isList', 0)
            ->set('date_modified.attr.isList', 0)
            ->set('id.attr.isView', 0);
    }
}
