<?php
/**
 * Qwin Framework
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
 */

/**
 * Get
 * 
 * @package     Qwin
 * @subpackage  Widget
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @author      Twin Huang <twinh@yahoo.cn>
 * @since       2011-02-05 21:01:08
 * @todo        优化
 */
class Qwin_Hook extends Qwin_Widget {

    /**
     * 选项
     * @var array
     * 
     *       paths      array       钩子目录
     * 
     *       expire     int         缓存有效期
     */
    public $options = array(
        'paths'     => array(),
        'expire'    => 86400,
    );
    
    /**
     * 过滤器列表
     * @var array 
     */
    public $filters = array();
    
    /**
     * 事件列表
     * @var array
     */
    public $events = array();

    /**
     * 钩子数据
     * @var array
     */
    protected $_data = array();

    public function __construct($source = null)
    {
        // 初始化选项
        parent::__construct($source);
        $options = &$this->options;

        // 设置默认目录
        if (empty($options['paths'])) {
            $options['paths'] = array(
                dirname(dirname(dirname(__FILE__))) . '/widgets/'
            );
        }

        // 获取钩子缓存
        $data = $this->cache->get('hook');
        if (!$data) {
            $files = array();
            foreach ($options['paths'] as $path) {
                $this->findHooks($path);
            }
            
            // 根据优先级排序
            foreach ($this->events as &$value) {
                ksort($value);
            }
            unset($value);

            foreach ($this->filters as &$value) {
                ksort($value);
            }
            
            $this->cache->set('hook', array(
                'filters' => $this->filters,
                'events' => $this->events,
            ), $options['expire']);
        } else {
            $this->filters = $data['filters'];
            $this->events = $data['events'];
        }
    }

    /**
     * 查找钩子
     *  
     * @param string $dir 当前查找目录
     * @param string $root 根目录
     * @todo 整理优化
     */
    public function findHooks($dir, $root = null)
    {
        if (null == $root) {
            $root = $dir;
        }
        
        $return = array();
        $files = glob($dir . '/*');
        foreach ($files as $file) {
            if (is_dir($file)) {
                $this->findHooks($file, $root);
                //$return = array_merge($return, $this->findFiles($file, $root));
            } else {
                if ('.php' == substr($file, -4)) {
                    // 过滤非类文件
                    $name = basename($file);
                    $ord = ord($name[0]);
                    if (65 > ord($name[0]) || 90 < ord($name[0])) {
                        continue;
                    }
                    
                    // 根据文件名称和根目录的相对路径,反向获取类名称
                    $class = substr($file, strlen($root), -4);
                    $class = ltrim($class, '/\\');
                    $class = strtr($class, '/', '_');

                    require_once $file;
                    if (!class_exists($class)) {
                        continue;
                    }

                    $vars = get_class_vars($class);
                    $priorities = isset($vars['priorities']) ? (array)$vars['priorities'] : array();
                    $methods = get_class_methods($class);
                    
                    foreach ($methods as $method) {
                        $method = strtolower($method);
                        // 处理事件
                        if (7 < strlen($method) && 'trigger' == substr($method, 0, 7)) {
                            $name = strtolower(substr($method, 7));
                            
                            !isset($priorities[$method]) && $priorities[$method] = 50;
                            while (isset($this->events[$name][$priorities[$method]])) {
                                $priorities[$method]++;
                            }
                            $this->events[$name][$priorities[$method]] = array(
                                'file' => realpath($file),
                                'class' => strtolower($class),
                            );
                            
                        // 处理过滤器
                        } elseif (6 < strlen($method) && 'filter' == substr($method, 0, 6)) {
                            $name = strtolower(substr($method, 6));
                            
                            !isset($priorities[$method]) && $priorities[$method] = 50;
                            while (isset($this->filters[$name][$priorities[$method]])) {
                                $priorities[$method]++;
                            }
                            $this->filters[$name][$priorities[$method]] = array(
                                'file' => realpath($file),
                                'class' => strtolower($class),
                            );
                        }
                    }
                }
            }
        }
        
        return $this;
    }
    
    public function call()
    {
        return $this;
    }
}
