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
 * @since       2011-08-22 23:44:15
 */

class Doc_Controller extends Controller_Widget
{
    public function actionIndex()
    {
        $request = $this->_request;
        $class = $request->get('class');

        if (empty($class)) {
            $this->_view->alert('Class should not be empty!');
        }
        
        if (!class_exists($class)) {
            $this->_view->alert('Class "' . $class . '" not found.');
        }
        
        $object = new ReflectionClass($class);
        $comment = new ReflectionComment($object->getDocComment());
        
        $data = array(
            'overview' => array(
                'name' => $comment->getTag('name'),
                'version' => $comment->getTag('version'),
                'description' => $comment->getTag('description'),
            ),
        );
        
        $this->_view->assign(get_defined_vars());
    }
}