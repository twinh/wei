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
 * Minify
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
     * 存储文件的二维数组,键名为文件类型,如js,css,值为文件路径数组
     *
     * @var array
     */
    protected $_files = array();

    /**
     * 缓存对象
     *
     * @var Qwin_Storable
     */
    protected $_cache;

    /**
     * 测试输出的模板
     *
     * @var array
     */
    protected $_tpls = array(
        'js' => '<script type="text/javascript" src="%s"></script>',
        'css' => '<link rel="stylesheet" type="text/css" media="all" href="%s" />',
    );

    /**
     * 初始化
     */
    public function  __construct(array $options = array())
    {
        parent::__construct($options);

        // todo 缓存类型,参数
        $this->_cache = $this->fcache;
    }

    public function __invoke($file)
    {
        return $this->add($file);
    }

    /**
     * 添加一个或多个文件
     *
     * @param string $file 文件路径
     * @return Qwin_Minify
     */
    public function add($file)
    {
        if (is_array($file)) {
            foreach ($file as $item) {
                $this->add($item);
            }
        } elseif (is_file($file)) {
            $ext = pathinfo($file, PATHINFO_EXTENSION);

            $this->_files[$ext][] = $file;
        }
        return $this;
    }

    /**
     * 打包某一类后缀的文件
     *
     * @param string $ext 后缀名
     * @return string 打包后的名称
     */
    public function pack($ext)
    {
        if (!isset($this->_files[$ext]) || empty($this->_files[$ext])) {
            return false;
        }

        $this->_files[$ext] = array_unique($this->_files[$ext]);

        $name = md5(implode('|', $this->_files[$ext]));

        if (!$this->_cache->get('minify_' . $name)) {
            $this->_cache->set('minify_' . $name, $this->_files[$ext]);
        }

        return $name;
    }

    /**
     * 根据名称获取文件数组
     *
     * @param string $name 名称
     * @return array|false
     */
    public function getFiles($name)
    {
        return $this->_cache->get('minify_' . $name);
    }

    /**
     * 打包一类文件
     *
     * @param string $extension 后缀名
     * @return string 打包的名称
     */
    public function output($ext)
    {
        if (isset($tpl[$ext])) {
            if (isset($tpl[$ext])) {
                foreach ($this->_files[$ext] as $file) {
                    echo sprintf($tpl[$ext], $file);
                }
            } else {
                return false;
            }
        } else {
            return $this->exception('Unsupport output type "' . $ext . '"');
        }
    }
}
