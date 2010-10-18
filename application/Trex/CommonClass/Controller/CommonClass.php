<?php
/**
 * Common Class
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
 * @subpackage  CommonClass
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2009-11-21 12:18:00
 */

class Trex_CommonClass_Controller_CommonClass extends Trex_ActionController
{
    /**
     * on 函数
     */
    public function onAfterDb($data)
    {
        Qwin::run('Project_Helper_CommonClass')->write($data);
    }

    /**
     * db 转换函数
     * @todo 转义还原, map的安全检查,结构应该是二维 stdClass
     */
    public function convertDbValue($val, $name, $row, $row_copy)
    {
        $val = str_replace('\"', '"', $val);
        $data = Qwin::run('-arr')->jsonDecode($val, 'pear');
        return serialize($data);
    }

    public function convertListOperation($value, $name, $data, $copyData)
    {
        $primaryKey = $this->_meta['db']['primaryKey'];
        $url = $this->url->createUrl($this->_set, array('action' => 'Add', '_data[sign]' => $copyData['sign']));
        $html = Qwin_Helper_Html::jQueryButton($url, $this->_lang->t('LBL_ACTION_ADD_NEXT'), 'ui-icon-plusthick')
              . parent::convertListOperation($value, $name, $data, $copyData);
        return $html;
    }
}
