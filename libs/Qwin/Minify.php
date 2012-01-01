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
 * CrudController
 * 
 * @package     Qwin
 * @subpackage  Application
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @author      Twin Huang <twinh@yahoo.cn>
 * @since       2011-01-31 14:24:51
 */
class Qwin_Minify extends Qwin_Widget
{
    /**
     * 
     * @var array       存储文件路径的数组
     *
     *      -- js       js文件路径
     *
     *      -- css      css文件路径
     *
     *      -- ...      其他文件类型,也许是php等等
     */
    protected $_data = array(
        'js'    => array(),
        'css'   => array(),
    );
    
    /**
     * 缓存目录
     * @var string
     */
    protected $_cacheDir;

    /**
     * 初始化系统
     *
     * @param string $cachePath 缓存文件的路径
     */
    public function  __construct(array $options = null)
    {
        parent::__construct($options);
        $this->_cacheDir = $this->cache->options['dir'] . 'minify/';
    }
    
    public function call($options = null)
    {
        return $this->add($options);
    }

    /**
     * 添加一个文件
     *
     * @param string $file 文件路径
     * @param string $extension 后缀名,如果为设置,则自动获取
     * @return Widget_Minify 当前对象
     * @todo 未加入时,是否应该返回false
     */
    public function add($file, $extension = null)
    {
        if (is_array($file)) {
            foreach ($file as $item) {
                $this->add($item);
            }
        } elseif (file_exists($file)) {
            if (null == $extension) {
                $extension = pathinfo($file, PATHINFO_EXTENSION);
            }
//            if ('js' == $extension) {
//                echo '<script src="' . $file . '"></script>';
//            } else {
//                echo '<link rel="stylesheet" type="text/css" media="all" href="' . $file . '" />';
//            }
            if (isset($this->_data[$extension])) {
                $this->_data[$extension][] = $file;
            }
        }
        return $this;
    }

    /**
     * 打包一类文件
     *
     * @param string $extension 后缀名
     * @return string 打包的名称
     */
    public function pack($extension)
    {
        if (!isset($this->_data[$extension]) || empty($this->_data[$extension])) {
            return false;
        }
        $this->_data[$extension] = array_unique($this->_data[$extension]);
        $name = md5(implode('|', $this->_data[$extension]));
        $fileName = $this->_cacheDir . $name . '.php';
        if (file_exists($fileName)) {
            return $name;
        }
        file_put_contents($fileName, '<?php return ' . var_export($this->_data[$extension], true) . ';' );

        return $name;
    }

    /**
     * 获取缓存文件
     *
     * @param string $name 名称
     * @return string|false
     */
    public function getCacheFile($name)
    {
        $file = $this->_cacheDir . $name . '.php';
        if (file_exists($file)) {
            return $file;
        }
        return false;
    }
}
