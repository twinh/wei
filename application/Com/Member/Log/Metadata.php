<?php
/**
 * Log
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
 * @package     Com
 * @subpackage  Member
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-05-24 07:52:43
 */

class Com_Member_Log_Meta extends Com_Meta
{
    public function setMeta()
    {
        $this->setIdMeta();
        $this->merge(array(
            'field' => array(
                'member_id' => array(
                    'attr' => array(
                        'isLink' => 1,
                        'isList' => 1,
                    ),
                ),
                'ip' => array(
                    'attr' => array(
                        'isList' => 1,
                    ),
                ),
                'date_created' => array(
                    'attr' => array(
                        'isList' => 1,
                    ),
                ),
            ),
            'group' => array(

            ),
            'model' => array(
                'member' => array(
                    'module' => 'com/member',
                    'alias' => 'member',
                    'local' => 'member_id',
                    'foreign' => 'id',
                    'type' => 'view',
                    'fieldMap' => array(
                        'member_id' => 'username',
                    ),
                ),
            ),
            'db' => array(
                'table' => 'member_log',
                'order' => array(
                    array('date_created', 'DESC'),
                ),
            ),
            'page' => array(
                'title' => 'LBL_MODULE_MEMBER_LOGINLOG',
            )
        ));
    }

    public function sanitiseDbIp($value, $name, $data, $copyData)
    {
        return Qwin_Util_Common::getIp();
    }

    public function sanitiseListDateCreated($value, $name, $data, $copyData)
    {
        return $value;
    }
}
