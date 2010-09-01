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
 * @package     Trex
 * @subpackage  Member
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-05-24 07:52:43
 */

class Trex_Member_Metadata_Log extends Qwin_Trex_Metadata
{
    public function  __construct()
    {
        $this->parseMetadata(array(
            // 基本属性
            'field' => array(
                'username' => array(
                    'form' => array(
                        'name' => 'username',
                    ),
                    'attr' => array(
                        'isList' => 0,
                    ),
                    'validator' => array(
                        'rule' => array(
                            'required' => true,
                        ),
                    ),
                ),
                'password' => array(
                    'form' => array(
                        'name' => 'password',
                    ),
                    'attr' => array(
                        'isList' => 0,
                    ),
                    'converter' => array(
                        'db' => array('md5')
                    )
                ),
                /*
                'captcha' => array(
                    'form' => array(
                        'name' => 'captcha',
                    ),
                    'attr' => array(
                        'isList' => 0,
                        'isDbField' => 0,
                        'isDbQuery' => 0,
                    ),
                    'validator' => array(
                        'rule' => array(
                            'required' => true,
                        ),
                    ),
                ),*/
            ),
            'model' => array(
                
            ),
            'db' => array(
                'table' => 'member',
            ),
            // 页面显示
            'page' => array(
                'title' => 'LBL_MODULE_TITLE',
            ),
        ));
    }
}
