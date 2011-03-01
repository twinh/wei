<?php
/**
 * EditForm
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
 * @since       2010-10-14 17:38:02
 */

class Common_View_Edit extends Common_View
{
    public function  preDisplay()
    {
        parent::preDisplay();
        $this->setElement('content', '<resource><theme>/<defaultNamespace>/element/common/form<suffix>');
        
        // 初始化变量,方便调用
        $primaryKey = $this->primaryKey;

        $meta = $this->meta;
        $data = $this->data;
        $asc = Qwin::config('asc');

        /* @var $formWidget Form_Widget */
        $formWidget = Qwin::widget('form');
        $formOption = array(
            'meta'  => $meta,
            'action' => 'edit',
            'data'  => $this->data,
        );
        
        $operationField = $this->loadWidget('Common_Widget_FormLink', array($this->data, $primaryKey));

        $this->assign(get_defined_vars());
    }
}
