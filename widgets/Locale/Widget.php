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
 * @since       2011-6-11 16:52:37
 * @todo        度量衡,纸张大小,字符等
 */

class Locale_Widget extends Qwin_Widget_Abstract
{
    /**
     * 默认选项
     * @var array
     */
    protected $_defaults = array(
        'timezone' => 'Asia/Shanghai',
    );

    /**
     * 初始化
     * 
     * @param array $options 选项
     */
    public function __construct(array $options = array())
    {
        parent::__construct($options);
        
        // 设置默认时区
        date_default_timezone_set($options['timezone']);
    }
    
    /**
     * 转换日期时间格式
     * 
     * @param string $value 日期时间
     * @param array $options 选项
     * @return string 日期时间
     */
    public function date($value, array $options = array())
    {
        return $value;
    }
    
    /**
     * 转换时间格式
     * 
     * @param string $value 日期
     * @param array $options 选项
     * @return string 日期
     */
    public function time($value, array $options = array())
    {
        return $value;
    }
    
    /**
     * 转换时间格式
     * 
     * @param string $value 日期
     * @param array $options 选项
     * @return string 日期
     */
    public function number($value, array $options = array())
    {
        return $value;
    }
    
    /**
     * 转换货币格式
     * 
     * @param string $value 货币
     * @param array $options 选项
     * @return string 货币
     */
    public function money($value, array $options = array())
    {
        return $value;
    }
}