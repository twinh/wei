<?php
/**
 * Controller
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
 * @package     Public
 * @subpackage  Controller
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-09-03 8:07:29
 */

class Public_Controller extends Qwin_Trex_Controller
{
    public function __construct()
    {
        Qwin::run('Trex_Namespace');
        $ini = Qwin::run('-ini');
        $this->request = Qwin::run('Qwin_Request');
        $this->url = Qwin::run('Qwin_Url');
        $set = $this->_set = $ini->getSet();
        $this->_config = $ini->getConfig();
        $this->session = Qwin::run('Qwin_Session');
        $this->member = $this->session->get('member');

        // 元数据管理助手,负责元数据的获取和转换
        $this->metaHelper = Qwin::run('Qwin_Trex_Metadata');

        $this->_meta = $this->metaHelper->getMetadataBySet($set);
    }

    public function setRedirectView($message, $method = null, $dispaly = true)
    {
        $this->_view['class'] = 'Public_View';
        $this->_view['data']['message'] = $message;
        $this->_view['data']['method'] = $method;
        $this->_view['element'] = array(
            array('content', QWIN_ROOT_PATH . '/view/common/redirect.php'),
        );

        if(true != $dispaly)
        {
            return $this;
        }
        return $this->loadView()->display();
    }
}
