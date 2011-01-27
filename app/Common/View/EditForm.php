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
 * @package     Qwin
 * @subpackage  
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-10-14 17:38:02
 */

class Common_View_EditForm extends Qwin_App_View_Processer
{
    public function __construct(Qwin_App_View $view)
    {
        $view->setElement('content', array(
            '<resource><theme>/<defaultNamespace>/element/<defaultModule>/<defaultController>-form<suffix>',
        ));
        
        // 初始化变量,方便调用
        $primaryKey = $view->primaryKey;
        $meta = $view->meta;
        $metaHelper = $view->metaHelper;
        $data = $view->data;
        $config = Qwin::run('-config');
        $asc = $config['asc'];

        $orderedFeid = $metaHelper->orderField($meta);
        $layout = $metaHelper->getTableLayout($meta, $orderedFeid, 'edit', $meta['page']['tableLayout']);

        $group = $meta['group'];
        $jQueryValidateCode = Qwin_Helper_Array::jsonEncode($metaHelper->getJQueryValidateCode($meta));

        $view->assign(get_defined_vars());
    }
}
