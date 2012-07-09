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
     * Options
     *
     * @var array
     *
     *       dir    string  Cache directory
     */
    public $options = array(
        'dir'       => './cache',
    );

    /**
     * Illegal chars as the name of cache, would be replaced to "_"
     *
     * @var array
     * @todo others chars ?
     */
    protected $_illegalChars = array(
        '\\', '/', ':', '?', '"', '<', '>', '|', "\r", "\n"
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
                return $this->error('Failed to create directory: ' . $dir );
                // @codeCoverageIgnoreEnd
            }
            umask($mask);
        }
        $this->options['dir'] = $dir;
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
     * @param string $key the name of cache
     * @param mixed $value the value of cache
     * @param int $expire expire time (seconds)
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
     * @param string $key the name of cache
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
     * @param string $key the name of cache
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
        ));

        return (bool)file_put_contents($file, $content, LOCK_EX);
    }

    /**
     * Replace cache, if cache not exists, return false
     *
     * @param string $key the name of cache
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
     * @param string $key the name of cache
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
     * Get cache file by key
     *
     * @param string $key
     * @return string
     */
    public function getFile($key)
    {
        $key = str_replace($this->_illegalChars, '_', $key);
        return $this->options['dir'] . '/' . $key . '.tmp';
    }

    /**
     * Increase a numerical cache
     *
     * @param string $key the name of cache
     * @param int $offset the value to decrease
     * @return int|false
     */
    public function increment($key, $offset = 1)
    {
        $file = $this->getFile($key);

        if (!is_file($file)) {
            return false;
        }

        // open file for reading and rewiting
        if (!$handle = fopen($file , 'r+b')) {
            return false;
        }

        // lock for rewiting
        if (!flock($handle, LOCK_EX)) {
            return false;
        }

        // read content
        $content = fread($handle, filesize($file));
        $content = @unserialize($content);

        // check if content is valid
        if (!$content || !is_array($content) || time() > $content[0]) {
            return false;
        }

        if (!is_numeric($content[1])) {
            return false;
        }

        // prepare file content
        $content[1] += $offset;
        $result = $content[1];
        $content = serialize($content);

        // rewrite content
        rewind($handle);
        if (false === fwrite($handle, $content)) {
            return false;
        }
        flock($handle, LOCK_UN);
        fclose($handle);

        return $result;
    }

    /**
     * Decrease a numerical cache
     *
     * @param string $key the name of cache
     * @param int $offset the value to decrease
     * @return int|false
     */
    public function decrement($key, $offset = 1)
    {
        return $this->increment($key, -$offset);
    }

    /**
     * Clear all cache
     *
     * @return boolean
     */
    public function clear()
    {
        $result = true;

        foreach(glob($this->options['dir']. '/' . '*.tmp') as $file){
            $result = $result && @unlink($file);
        }

        return $result;
    }
}