<?php
/**
 * Widget
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
 * @since       2011-05-11 01:09:44
 */

class Form2_Widget extends Qwin_Widget_Abstract
{
    /**
     * @var array           默认选项
     * 
     *      -- meta         元数据对象
     * 
     *      -- id           主键的值
     * 
     *      -- data         初始值
     * 
     *      -- sanitise     转换配置
     * 
     *      -- display      是否显示视图
     */
    protected $_defaults = array(
        'meta'      => null,
        'name'      => 'form',
        'id'        => null,
        'data'      => array(),
        'asAction'  => 'view',
        'isView'    => true,
        'sanitise'  => array(
            
        ),
        'display'   => true,
    );
    
    public function render($options)
    {
        // 初始配置
        $options    = $options + $this->_options;
        
        // 检查元数据是否合法
        $meta = $options['meta'];
        if (false === Qwin_Meta::isValid($meta)) {
            $this->e('ERR_META_NOT_DEFINED');
        }
        
        // 检查元数据中是否包含表单定义
        if (!$meta->offsetLoad($options['name'], 'form')) {
            return $this->e('ERR_META_OFFSET_NOT_FOUND', $options['name']);
        }
        
        qw_p($meta['form']);
    }
}
