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
 * FCache
 *
 * @package     Qwin
 * @subpackage  Widget
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @author      Twin Huang <twinh@yahoo.cn>
 * @since       2011-10-23
 */
class Qwin_Fcache extends Qwin_Widget implements Qwin_Storable
{
    /**
     * @var array Options
     *
     *       dir    string  Cache dir
     */
    public $options = array(
        'dir'       => './cache',
    );

    public function __construct($options = null)
    {
        $options = (array)$options + $this->options;
        $this->option($options);
    }

    /**
     * Set cache dir
     *
     * @param string $dir
     * @return Qwin_Fcache
     */
    public function setDirOption($dir)
    {
        if (!is_dir($dir)) {
            $mask = umask(0);
            if (!@mkdir($dir, 0777, true)) {
                // how to test it ?
                // @codeCoverageIgnoreStart
                umask($mask);
                return $this->error('Failed to creat directory: ' . $dir );
                // @codeCoverageIgnoreEnd
            }
            umask($mask);
        }
        return $this;
    }

    /**
     * Get or set cache
     *
     * @param string $key the name of cache
     * @param mixed $value
     * @param int $expire expire time for set cache
     * @return Qwin_Fcache
     */
    public function __invoke($key, $value = null, $expire = 0)
    {
        if (1 == func_num_args()) {
            return $this->get($key);
        } else {
            return $this->set($key, $value, $expire);
        }
    }

    /**
     * Add cache, if cache is exists, return false
     *
     * @param string $key the key of cache
     * @param mixed $value the value of cache
     * @param int $expire expire time
     * @return bool
     */
    public function add($key, $value, $expire = 0, array $options = array())
    {
        if ($this->get($key)) {
            return false;
        }
        return $this->set($key, $value, $expire);
    }

    /**
     * Get cache
     *
     * @param string $key the key of cache
     * @return mixed|false
     */
    public function get($key, $options = null)
    {
        $file = $this->getFile($key);

        if (!is_file($file)) {
            return false;
        }

        $content = @unserialize(file_get_contents($file));
        if (!$content || !is_array($content) || time() > $content[0]) {
            return false;
        }

        return $content[1];
    }

    /**
     * Set cache
     *
     * @param string $key the key of cache
     * @param value $value the value of cache
     * @param int $expire expire time, 0 means never expired
     * @return bool
     */
    public function set($key, $value, $expire = 0, array $options = array())
    {
        $file = $this->getFile($key);

        // 2147483647 = pow(2, 31) - 1
        // avoid year 2038 problem in 32-bit system when date coverts or compares
        // @see http://en.wikipedia.org/wiki/Year_2038_problem
        $content = serialize(array(
            0 => $expire ? time() + $expire : 2147483647,
            1 => $value,
            2 => $key,
        ));

        return (bool)file_put_contents($file, $content);
    }

    /**
     * Replace cache, if cache not exists, return false
     *
     * @param string $key the key of cache
     * @param mixed $value the value of cache
     * @param int $expire expire time
     * @return bool
     */
    public function replace($key, $value, $expire = 0, array $options = array())
    {
        if (!$this->get($key)) {
            return false;
        }
        return $this->set($key, $value, $expire);
    }

    /**
     * Remove cache by key
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
     * Check if cache file expired
     *
     * @param string $key 名称
     * @return bool
     */
    public function isExpired($key)
    {
        return $this->get($key) ? false : true;
    }

    /**
     * Get cache file by cache key
     *
     * @param string $key
     * @return string
     */
    public function getFile($key)
    {
        return $this->options['dir'] . '/' . $key . '.tmp';
    }

    public function delete($key)
    {
        $file = $this->getFile($key);

        return unlink($file);
    }

    public function decrement($key, $offset = 1)
    {
        return $this->set($key, $this->get($key) + $offset);
    }

    public function increment($key, $offset = 1)
    {
        return $this->set($key, $this->get($key) - $offset);
    }

    public function clear()
    {
        foreach(glob($this->options['dir']. '/' . '*.tmp') as $file){
            @unlink($file);
        }
    }
}
