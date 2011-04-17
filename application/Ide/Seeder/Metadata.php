<?php
/**
 * Metadata
 *
 * Copyright (c) 2008-2011 Twin Huang. All rights reserved.
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
 * @since       2011-04-17 13:30:29
 */

class Ide_Seeder_Metadata extends Com_Metadata
{
    public function setMetadata()
    {
        $this->merge(array(
            'field' => array(
                'module' => array(
                    'form' => array(
                        '_type' => 'select',
                        '_resourceGetter' => array(
                            array('Ide_Seeder_Model', 'getModuleResource'),
                        ),
                    ),
                ),
                'number' => array(
                    'form' => array(
                        '_value' => '1000',
                    ),
                ),
            ),
            'group' => array(

            ),
            'db' => array(

            ),
            'page' => array(
                'title' => 'MOD_IDE_SEEDER',
            ),
        ));
    }

    public function sanitisePostNumber($value)
    {
        $value = intval($value);
        $value <= 0 && $value = $this['number']['form']['_value'];
        return $value;
    }
}