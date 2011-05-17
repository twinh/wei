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
 * @since       2011-05-17 11:25:29
 */

class Ide_Meta_Controller extends Com_Controller
{
    public function actionIndex()
    {
        
    }
    
    public function actionFields()
    {
        $request = $this->_request;
        
        $from = $request->get('from');
        $from = Qwin_Util_Array::forceInArray($from, array('file', 'table'));
        $source = $request->get('source');
        
        // 读取元数据域配置文件
        if ('file' == $from) {
            
        } else {
            
        }
        
        $meta = $this->getMeta();
        $this->getView()->assign(get_defined_vars());
    }
    
    public function actionList()
    {
        
    }
    
    public function actionForm()
    {
        
    }
    
    public function actionDb()
    {
        
    }
    
    public function actionPage()
    {
        
    }
    
    public function actionMeta()
    {
        
    }
}