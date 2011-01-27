<?php
/**
 * ViewedItem
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
 * @subpackage  Widget
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2011-01-03 01:28:50
 */

class Common_Widget_ViewedItem extends Common_Widget
{
    public function render($param, $view)
    {
        $lastViewedItem = Qwin::run('-session')->get('lastViewedItem');
        $view->assign('lastViewedItem', $lastViewedItem);

        require $view->decodePath('<resource><theme>/<defaultNamespace>/element/widget/viewed-item<suffix>');
    }
}
