<?php
/**
 * Email
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
 * @package     Common
 * @subpackage  Email
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-09-20 10:37:51
 */

class Common_Email_Metadata_Email extends Common_Metadata
{
    public function setMetadata()
    {
        $this->setIdMetadata()
            ->setCreatedData()
            ->setOperationMetadata();
        $this->parseMetadata(array(
            'field' => array(
                'from' => array(
                    'attr' => array(
                        'isList' => 1,
                    ),
                    'validator' => array(
                        'rule' => array(
                            'required' => true,
                            'email' => true,
                        ),
                    ),
                ),
                'from_name' => array(
                    
                ),
                'to' => array(
                    'attr' => array(
                        'isList' => 1,
                    ),
                    'validator' => array(
                        'rule' => array(
                            'required' => true,
                            'email' => true,
                        ),
                    ),
                ),
                'to_name' => array(
                ),
                'subject' => array(
                    'attr' => array(
                        'isList' => 1,
                    ),
                ),
                'content' => array(
                    'form' => array(
                        '_type' => 'textarea',
                        '_widget' => 'CKEditor',
                    ),
                ),
                'result' => array(
                    'attr' => array(
                        'isList' => 1,
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
                'table' => 'email',
                'order' => array(
                    array('date_created', 'DESC')
                )
            ),
            'page' => array(
                'title' => 'LBL_MODULE_EMAIL',
            ),
        ));
    }

    public function filterListTo($value, $name, $data, $copyData)
    {
        return $copyData['to_name'] . '&lt;' . $copyData['to'] . '&gt;';
    }

    public function filterListFrom($value, $name, $data, $copyData)
    {
        return $copyData['from_name'] . '&lt;' . $copyData['from'] . '&gt;';
    }
}
