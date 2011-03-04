<?php
/**
 * Zhcn
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
 * @package     QWIN_PATH
 * @subpackage  
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-09-20 17:09:38
 */

class Common_Email_Language_Zhcn extends Common_Language_Zhcn
{
    public function  __construct()
    {
        parent::__construct();
        $this->_data += array(
            'FLD_FROM' => '来自',
            'FLD_FROM_NAME' => '发件人',
            'FLD_TO' => '发至',
            'FLD_TO_NAME' => '收件人',
            'FLD_SUBJECT' => '标题',
            'FLD_RESULT' => '发送结果',

            'LBL_ACTION_POST' => '发布',

            'LBL_MODULE_EMAIL' => '邮件',
        );
    }
}
