<?php
/**
 * Form
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
 * @package     Common
 * @subpackage  View
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-09-17 18:14:58
 */

class Common_View_AddForm extends Qwin_Application_View_Processer
{
    public function __construct(Qwin_Application_View $view)
    {

        $view->setElement('content', '<resource><theme>/<defaultNamespace>/element/<defaultModule>/<defaultController>-form<suffix>');

        // 初始化变量,方便调用
        $primaryKey = $view->primaryKey;
        $meta = $view->meta;
        $metaHelper = $view->metaHelper;
        $data = $view->data;
        $config = Qwin::run('-config');
        $asc = $config['asc'];

        $orderedFeid = $metaHelper->orderField($meta);
        $layout = $metaHelper->getTableLayout($meta, $orderedFeid, 'add', $meta['page']['tableLayout']);

        $group = $meta['group'];
        $jQueryValidateCode = Qwin_Helper_Array::jsonEncode($metaHelper->getJQueryValidateCode($meta));

        $view->assignList(get_defined_vars());
    }
}
