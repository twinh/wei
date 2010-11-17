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
 * @since       2009-11-21 15:21:00
 */

class Trex_CommonClass_Metadata_CommonClass extends Trex_Metadata
{
    public function  setMetadata()
    {
        $this->setCommonMetadata()
            ->parseMetadata(
            array(
                // 基本属性
                'field' => array(
                    'language' => array(
                    ),
                    'sign' => array(
                    ),
                    'code' => array(
                        'form' => array(
                            '_type' => 'textarea',
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
                    'table' => 'common_class',
                    'order' => array(
                        array('date_created', 'DESC'),
                    ),
                    'limit' => 10,
                ),
                // 页面显示
                'page' => array(
                    'title' => 'LBL_MODULE_COMMONCLASS',
                ),
         ));
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
        $primaryKey = $this->db['primaryKey'];
        $url = Qwin::run('-url');
        $lang = Qwin::run('-lang');
        $set = $this->getSetFromClass();
        $link = $url->createUrl($set, array('action' => 'Add', '_data[sign]' => $copyData['sign']));
        $html = Qwin_Helper_Html::jQueryButton($link, $lang->t('LBL_ACTION_ADD_NEXT'), 'ui-icon-plusthick')
              . parent::convertListOperation($value, $name, $data, $copyData);
        return $html;
    }
}
