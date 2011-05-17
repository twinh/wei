<?php
/**
 * Page
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
 * @subpackage  Meta
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-7-27 12:44:44
 */

class Qwin_Meta_Page extends Qwin_Meta_Common
{
    protected $_defaults = array(
        'title'         => null,
        'icon'          => 'document',
        'description'   => null,
        'tableLayout'   => 2,
        'mainField'     => null,
        // TODO 作为新的元数据键名
//        'trash'         => array(
//            'enable'        => true,
//            'field'         => 'is_deleted',
//            'toggleFlag'    => array(0, 1),
//        ),
//        'log'           => array(
//            'enable'        => true,
//            'field'         => '',
//            'banField'      => '',
//        ),
    );
    
    /**
     * 格式化数据,例如,设置默认数据,为未初始化的键名设置空值
     *
     * @param array $data 数据
     * @param array $options 选项
     * @return Qwin_Meta_Common 当前对象
     * @todo 是否一定要数组
     */
    public function merge($data, array $options = array())
    {
        $data = (array)$data;
        
        if (!isset($data['title'])) {
            $data['title'] = 'MOD_' . $this->getParent()->getModule()->toLang();
        }

        $data = $data + $this->_defaults;
        $this->exchangeArray($data + $this->getArrayCopy());
        return $this;
    }
}
