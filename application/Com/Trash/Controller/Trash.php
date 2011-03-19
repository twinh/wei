<?php
/**
 * Trash
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
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2011-01-09 14:21:39
 */

class Com_Trash_Controller_Trash extends Com_ActionController
{
    /**
     * 禁用行为
     * @var array
     * @todo 使用和父类同样的访问级别会被覆盖?
     */
    public $_unableAction = array(
        'add', 'edit',
    );

    protected $_validatorMessage;
    
    /**
     * 还原回收站记录
     */
    public function actionRestore()
    {
        $service = new Com_Trash_Service_Restore();
        $service->process(array(
            'asc' => $this->_asc,
            'data' => array(
                'primaryKeyValue' => $this->metaHelper->getUrlPrimaryKeyValue($this->_asc),
            ),
            'this' => $this,
        ));
    }

    /**
     * 删除(清空)回收站记录
     */
    public function actionDelete()
    {
        $service = new Com_Trash_Service_Delete();
        $service->process(array(
            'asc' => $this->_asc,
            'data' => array(
                'primaryKeyValue' => $this->request->getPrimaryKeyValue($this->_asc),
                'type' => $this->request->get('type'),
            ),
            'this' => $this,
        ));
    }
}
