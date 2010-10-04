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
 * @subpackage  Company
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-09-18 23:15:46
 */

class Trex_Company_Language_Enus extends Trex_Language_Enus
{
    public function  __construct()
    {
        parent::__construct();
        $this->_data += array(
            'LBL_FIELD_INDUSTRY' => 'Industry',
            'LBL_FIELD_NATURE' => 'Nature',
            'LBL_FIELD_SIZE' => 'Size',
            'LBL_FIELD_MEMBER_ID' => 'Member Id',
            'LBL_FIELD_MEMBER_NAME' => 'Member Name',

            'LBL_MODULE_COMPANY' => 'Company',
            'LBL_MODULE_MEMBER' => 'Member',
        );
    }
}