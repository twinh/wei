<?php
/**
 * Package
 *
 * Copyright (c) 2008-2012 Twin Huang. All rights reserved.
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
 * @subpackage  Management
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-09-16 16:15:23
 */

class Com_Management_Meta_Package extends Com_Meta
{
    public function __construct()
    {
        $this->setIdMeta();
        $this->setOperationMeta();
        $this->merge(array(
            'field' => array(
                'package' => array(
                    'validator' => array(
                        'rule' => array(
                            'required' => true,
                            'pathName' => true,
                        ),
                    ),
                ),
                'path' => array(

                ),
            ),
            'group' => array(

            ),
            'model' => array(
            ),
            'meta' => array(

            ),
            'db' => array(
                'table' => 'package',
            ),
            'page' => array(
                'title' => 'LBL_MODULE_NAMESPACE',
            ),
        ));
    }

     /**
     * 在列表操作下,为操作域设置按钮
     *
     * @param mixed 当前域的值
     * @param string 当前域的名称
     * @param array $data 已转换过的当前记录的值
     * @param array $cpoyData 未转换过的当前记录的值
     * @return string 当前域的新值
     */
    public function sanitiseListOperation($value, $name, $data, $copyData)
    {
        $primaryKey = $this->db['primaryKey'];
        $url = Qwin::call('-url');
        $lang = Qwin::call('-lang');
        $set = $this->getAsc();
        return Qwin_Util_Html::jQueryButton($url->url($set, array('controller' => 'Module', 'action' => 'Index', 'package_value' => $copyData['package'])), $lang->t('ACT_VIEW_MODULE'), 'ui-icon-lightbulb')
            . Qwin_Util_Html::jQueryButton($url->url($set, array('controller' => 'Module', 'action' => 'Add', 'package_value' => $copyData['package'])), $lang->t('ACT_ADD_MODULE'), 'ui-icon-plus')
            . Qwin_Util_Html::jQueryButton('javascript:if(confirm(QWIN_PATH.Lang.MSG_CONFIRM_TO_DELETE)){window.location=\'' . $url->url($set, array('action' => 'Delete', 'package_value' => $copyData['package'])) . '\';}', $lang->t('ACT_DELETE'), 'ui-icon-closethick');
    }
}

