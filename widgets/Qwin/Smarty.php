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
 * @since       2011-04-15 22:00:11 v0.7.9
 */

class Qwin_Smarty extends Qwin_Widget
{
    /**
     * Smarty对象
     * @var Smarty
     */
    protected $smarty;

    /**
     * 默认选项
     * @var array
     * @todo 更多选项
     */
    public $options = array(
        'compile_dir' => null,
    );
    
    public function __construct(array $options = array())
    {
        parent::__construct($options);
        
        require_once dirname(__FILE__) . '/Smarty/Smarty.class.php';
        $this->smarty = $this->qwin->call('Smarty');

        // 设定选项
        foreach ($this->options as $key => $value) {
            $this->smarty->$key = $value;
        }
    }
    
    public function call()
    {
        return $this->smarty;
    }

    /**
     * 通过魔术方法将微件的方法映射到Smarty对象的方法上.
     *
     * @param string $name 调用的方法名称
     * @param array $arguments 参数数组
     * @return mixed
     */
    public function  __call($name, $arguments)
    {
        return call_user_func_array(array($this->smarty, $name), $arguments);
    }

    /**
     * 获取Smarty对象
     * @return Smarty
     */
    public function getObject()
    {
        return $this->smarty;
    }
}
