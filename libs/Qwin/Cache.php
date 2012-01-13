<?php
/**
 * Qwin Framework
 *
 * Copyright (c) 2008-2012 Twin Huang. All rights reserved.
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
 */

/**
 * Cache
 * 
 * @package     Qwin
 * @subpackage  Widget
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @author      Twin Huang <twinh@yahoo.cn>
 * @since       2011-10-23 13:50:07
 * @todo        优化
 */
class Qwin_Cache extends Qwin_Widget
{
    /**
     * 选项
     * @var array
     *       dir    string  缓存存储路径 
     */
    public $options = array(
        'dir'       => './cache/',
    );
    
    public function __construct($source = null)
    {
        parent::__construct($source);
        // TODO r
        if (!is_dir($this->options['dir'])) {
            mkdir($this->options['dir']);
        }
    }
    
    /**
     * 设置或获取一项缓存
     * 
     * @param string $key 名称
     * @return mixed 
     */
    public function call($key)
    {
        $args = func_get_args();
        
        if (2 == count($args)) {
            return $this->set($key, $args[1]);
        } else {
            return $this->get($key);
        }
    }
    
    /**
     * 设置缓存,如果缓存已经存在且未过期,返回失败
     * 
     * @param string $key 名称
     * @param mixed $value 值
     * @param int $expire 过期时间
     * @return bool 
     */
    public function add($key, $value, $expire = 0)
    {
        if ($this->get($key)) {
            return false;
        }
        return $this->set($key, $value, $expire);
    }
    
    /**
     * 获取缓存
     * 
     * @param string $key 名称
     * @param mixed $default 默认值
     * @return mixed 
     */
    public function get($key, $default = null)
    {
        $file = $this->getFile($key);
        if (!is_file($file)) {
            return $default;
        }

        $content = @unserialize(file_get_contents($file));
        if (!$content || !is_array($content) || filemtime($file) < (time() - $content[0])) {
            return $default;
        }
        
        return $content[1];
    }
    
    /**
     * 设置缓存
     * 
     * @param string $key 名称
     * @param value $value 值
     * @param int $expire 过期时间,0为不过期
     * @return bool 
     */
    public function set($key, $value, $expire = 0)
    {
        $file = $this->getFile($key);
        
        $content = serialize(array(
            0 => time(),
            1 => $value,
            2 => $key,
        ));
        
        return file_put_contents($file, $content);
    }
    
    /**
     * 删除缓存
     * 
     * @param string $key
     * @return bool 
     */
    public function remove($key)
    {
        $file = $this->getFile($key);
        
        if (is_file($file)) {
            return unlink($file);
        }
        
        return false;
    }
    
    /**
     * 检查缓存是否过期
     * 
     * @param string $key 名称
     * @return bool
     */
    public function isExpired($key)
    {
        return $this->get($key) ? true : false;
    }
    
    /**
     * 根据键名获取文件名称
     * 
     * @param type $key
     * @return type 
     */
    public function getFile($key)
    {
        return $this->options['dir'] . '/' . $key;
    }
}
