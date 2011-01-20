<?php
/**
 * Code
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
 * @since       2011-01-20 20:47:21
 */

class Common_Option_Metadata_Code extends Common_Metadata
{
    public function setMetadata()
    {
        $this->parseMetadata(array(
            'field' => array(
                'value' => array(
                ),
                'name' => array(
                ),
                'color' => array(
                    'form' => array(
                        '_type' => 'select',
                        '_resource' => array(
                            '' => '无',
                            'red' => 'red',
                            'blue' => 'blue',
                            'green' => 'green',
                        ),
                    ),
                ),
                'style' => array(
                ),
            ),
        ));
    }

    public function getDynamicFieldForm($field, $key)
    {
        $form = $this->field[$field]['form'];
        $form['name'] = 'code[' . $key . '][' . $field . ']';
        return $form;
    }
}