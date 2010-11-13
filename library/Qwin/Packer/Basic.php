<?php
/**
 * Basic
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
 * @subpackage  Packer
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-11-10 20:24:19
 */

class Qwin_Packer_Basic
{
    /**
     * 打包的文件列表
     * @var array
     */
    protected $_file = array();

    protected $_cachePath;

    protected $_cacheAge = 31536000;

    protected $_pathCacheAge = 31536000;

    protected $_md5;

    /**
     * 设置缓存路径
     *
     * @param string $path 合法的缓存路径
     * @return object 当前对象
     */
    public function setCachePath($path)
    {
        $this->_cachePath = $path;
        return $this;
    }

    /**
     * 设置内容缓存时间
     *
     * @param int $age 缓存时间
     * @return object 当前对象
     */
    public function setCacheAge($age)
    {
        $this->_cacheAge = intval($age);
        return $this;
    }

    /**
     * 设置路径缓存时间
     *
     * @param int $age 缓存时间
     * @return object 当前对象
     */
    public function setPathCacheAge($age)
    {
        $this->_pathCacheAge = $age;
        return $this;
    }

    /**
     * 设置为调试模式,如果开启,文件总是会被重新创建
     *
     * @param string $flag 开启或关闭的标志
     * @return object 当前对象
     */
    public function setDebugModel($flag = true)
    {
        if(true == $flag)
        {
            $age = 0;
        } else {
            $age = 31536000;
        }
        $this->setCacheAge($age)
            ->setPathCacheAge(0);
        return $this;
    }

    /**
     * 添加文件
     *
     * @param string $file 文件路径
     * @return object 当前对象
     */
    public function add($file)
    {
        $this->_file[$file] = true;
        return $this;
    }

    /**
     * 删除文件
     *
     * @param string $file 文件名称
     * @return object 当前对象
     */
    public function delete($file)
    {
        if(isset($this->_file[$file]))
        {
            unset($this->_file[$file]);
        }
        return $this;
    }

    /**
     * 获取文件组的md5值,由各个文件的名称组成
     *
     * @return string md5值
     */
    public function md5()
    {
        $string = '';
        foreach($this->_file as $file => $value)
        {
            $string .= $file;
        }
        return $this->_md5 = md5($string);
    }

    /**
     * 打包文件,将文件路径存入缓存中
     *
     * @param string $name 缓存的名称
     * @return object 当前对象
     */
    public function pack($name = null, $rebuild = false)
    {
        // 空则不打包
        if(empty($this->_file))
        {
            return $this;
        }

        if(null == $name)
        {
            $name = $this->md5();
        }

        $path = $this->_cachePath . '/' .  $name . '-path.php';
        if(true == $rebuild
            || !file_exists($path)
            || 0 == $this->_pathCacheAge
            || $_SERVER['REQUEST_TIME'] - filemtime($path) > $this->_pathCacheAge)
        {
            $content = '<?php return \'' . serialize($this->_file) . '\';';
            file_put_contents($path, $content);
        }
        
        return $this;
    }

    /**
     * 获取经过打包的文件缓存内容
     *
     * @param string $name 缓存的名称
     * @return object 当前对象
     */
    public function getCache($name, $rebuild = false)
    {
        $path = $this->_cachePath . '/' .  $name . '-path.php';
        $packedFile = $this->_cachePath . '/' .  $name . '.php';

        if(file_exists($path))
        {
            $fileList = unserialize(require $path);
        } else {
            return false;
        }

        // 文件不存在,或过期,重新创建文件
        if(true == $rebuild
            || !file_exists($packedFile)
            || 0 != $this->_cacheAge
            || $_SERVER['REQUEST_TIME'] - filemtime($packedFile) > $this->_cacheAge)
        {
            $content = '';
            foreach($fileList as $file => $value)
            {
                if(file_exists($file))
                {
                    if(method_exists($this, 'convertContent'))
                    {
                        $content .= $this->convertContent(file_get_contents($file), $file);
                    } else {
                        $content .= file_get_contents($file);
                    }
                }
            }
            file_put_contents($packedFile, $content);
        } else {
            $content = file_get_contents($packedFile);
        }
        return $content;
    }

    /**
     * 缓存是否过期
     *
     * @param string $name 缓存名称
     * @return boolen 是否过期
     */
    public function isCacheExpired($name)
    {
        $packedFile = $this->_cachePath . '/' .  $name . '.php';
        if(!file_exists($packedFile)
            || 0 == $this->_cacheAge
            || $_SERVER['REQUEST_TIME'] - filemtime($packedFile) > $this->_cacheAge)
        {
            return true;
        }
        return false;
    }
}