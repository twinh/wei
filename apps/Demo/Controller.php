<?php
/**
 * Controller
 *
 * Copyright (c) 2008-2011 Twin Huang. All rights reserved.
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
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2011-9-19 11:58:57
 */
class Demo_Controller extends ActionController_Widget
{
    public function actionIndex()
    {
        $tag = $this->_request->get('tag');
        if (!$tag) {
            return $this->_view->alert('Tag name should not be empty.');
        }
        
        $data = Query_Widget::getByModule('demo')->where('tags = ?', $tag)->fetchArray();
        
        $tag = htmlspecialchars($tag);
        if (empty($data)) {
            return $this->_view->alert(sprintf('Tag "%s" does not have any demos.', $tag));
        }
        
        $this->_view->assign(get_defined_vars());
    }
    
    public function actionList()
    {
        return parent::actionIndex();
    }
}