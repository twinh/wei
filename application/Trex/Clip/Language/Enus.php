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
 * @subpackage  Clip
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-08-26 17:25:12
 */

class Trex_Clip_Language_Enus extends Trex_Language_Enus
{
    public function __construct()
    {
        parent::__construct();
        $this ->_data += array(
            'LBL_FIELD_FORM_TYPE' => 'Form Type',
            'LBL_FIELD_FORM_WIDGET' => 'Form Widget',

            'LBL_MODULE_CLIP' => 'Clip',
        );
    }
}
