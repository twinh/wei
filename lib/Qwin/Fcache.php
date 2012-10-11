<?php
/**
 * Qwin Framework
 *
 * @copyright   Copyright (c) 2008-2012 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Qwin;

/**
 * FCache
 *
 * @package     Qwin
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class Fcache extends Widget implements Storable
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
        '\\', '/', ':', '*', '?', '"', '<', '>', '|', "\r", "\n"
    );

    public function __construct($options = null)
    {
        $options = (array) $options + $this->options;
        $this->option($options);
    }

    /**
     * Set cache dir
     *
     * @param  string      $dir
     * @return Qwin_Fcache
     */
    public function setDirOption($dir)
    {
        if (!is_dir($dir)) {
            // @codeCoverageIgnoreStart
            if (!@mkdir($dir, 0644, true)) {
                return $this->exception('Failed to create directory: ' . $dir );
            }
            chmod($dir, 0644);
            // @codeCoverageIgnoreEnd
        }
        $this->options['dir'] = $dir;

        return $this;
    }

    /**
     * Get or set cache
     *
     * @param  string      $key    the name of cache
     * @param  mixed       $value
     * @param  int         $expire expire time for set cache
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
     * Get cache
     *
     * @param  string      $key the name of cache
     * @return mixed|false
     */
    public function get($key, $options = null)
    {
        $file = $this->getFile($key);

        if (!is_file($file)) {
            return false;
        }

        $content = @unserialize(file_get_contents($file));
        if ($content && is_array($content) && time() < $content[0]) {
            return $content[1];
        } else {
            $this->remove($key);

            return false;
        }
    }

    /**
     * Set cache
     *
     * @param  string $key    the name of cache
     * @param  value  $value  the value of cache
     * @param  int    $expire expire time, 0 means never expired
     * @return bool
     */
    public function set($key, $value, $expire = 0, array $options = array())
    {
        $file = $this->getFile($key);

        $content = $this->_prepareContent($value, $expire);

        return (bool) file_put_contents($file, $content, LOCK_EX);
    }

    /**
     * Add cache, if cache is exists, return false
     *
     * @param  string $key    the name of cache
     * @param  mixed  $value  the value of cache
     * @param  int    $expire expire time (seconds)
     * @return bool
     */
    public function add($key, $value, $expire = 0, array $options = array())
    {
        $file = $this->getFile($key);

        if (!is_file($file)) {
            // open and try to lock file immediately
            if (!$handle = $this->_openAndLock($file, 'wb', LOCK_EX | LOCK_NB)) {
                return false;
            }

            $content = $this->_prepareContent($value, $expire);

            return $this->_writeAndRelease($handle, $content);
        } else {
            // open file for reading and rewriting
            if (!$handle = $this->_openAndLock($file, 'r+b', LOCK_EX)) {
                return false;
            }

            // cache is not expired
            if ($this->_readAndVerify($handle, $file)) {
                fclose($handle);

                return false;
            }

            $content = $this->_prepareContent($value, $expire);

            return $this->_writeAndRelease($handle, $content, true);
        }
    }

    /**
     * Replace cache, if cache not exists, return false
     *
     * @param  string $key    the name of cache
     * @param  mixed  $value  the value of cache
     * @param  int    $expire expire time
     * @return bool
     */
    public function replace($key, $value, $expire = 0, array $options = array())
    {
        $file = $this->getFile($key);

        if (!is_file($file)) {
            return false;
        }

        // open file for reading and rewriting
        if (!$handle = $this->_openAndLock($file, 'r+b', LOCK_EX)) {
            return false;
        }

        if (!$this->_readAndVerify($handle, $file)) {
            fclose($handle);

            return false;
        }

        $content = $this->_prepareContent($value, $expire);

        return $this->_writeAndRelease($handle, $content, true);
    }

    /**
     * Remove cache by key
     *
     * @param  string $key the name of cache
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
     * @param  string $key
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
     * @param  string    $key    the name of cache
     * @param  int       $offset the value to decrease
     * @return int|false
     */
    public function increment($key, $offset = 1)
    {
        $file = $this->getFile($key);

        if (!is_file($file)) {
            return false;
        }

        // open file for reading and rewriting
        if (!$handle = $this->_openAndLock($file, 'r+b', LOCK_EX)) {
            return false;
        }

        if (!$content = $this->_readAndVerify($handle, $file)) {
            fclose($handle);

            return false;
        }

        if (!is_numeric($content[1])) {
            return false;
        }

        // prepare file content
        $result = $content[1] += $offset;
        $content = serialize($content);

        // rewrite content
        if ($this->_writeAndRelease($handle, $content, true)) {
            return $result;
        } else {
            return false;
        }
    }

    /**
     * Decrease a numerical cache
     *
     * @param  string    $key    the name of cache
     * @param  int       $offset the value to decrease
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

        foreach (glob($this->options['dir']. '/' . '*.tmp') as $file) {
            $result = $result && @unlink($file);
        }

        return $result;
    }

    /**
     * Open and lock file
     *
     * @param  string         $file      file path
     * @param  string         $mode      open mode
     * @param  int            $operation lock operation
     * @return false|resource false or file handle
     */
    protected function _openAndLock($file, $mode, $operation)
    {
        if (!$handle = fopen($file , $mode)) {
            return false;
        }

        if (!flock($handle, $operation)) {
            fclose($handle);

            return false;
        }

        return $handle;
    }

    /**
     * Read file by handle and verify if content is expired
     *
     * @param  resource    $handle file handle
     * @param  string      $file   file path
     * @return false|array false or file content array
     */
    protected function _readAndVerify($handle, $file)
    {
        // read all content
        $content = fread($handle, filesize($file));
        $content = @unserialize($content);

        // check if content is valid
        if ($content && is_array($content) && time() < $content[0]) {
            return $content;
        } else {
            return false;
        }
    }

    /**
     * Prepare content for writing
     *
     * @param  string $content the value of cache
     * @param  int    $expire  expire time
     * @return string file content
     */
    protected function _prepareContent($content, $expire)
    {
        // 2147483647 = pow(2, 31) - 1
        // avoid year 2038 problem in 32-bit system when date coverts or compares
        // @see http://en.wikipedia.org/wiki/Year_2038_problem
        return serialize(array(
            0 => $expire ? time() + $expire : 2147483647,
            1 => $content,
        ));
    }

    /**
     * Write content, release lock and close file
     *
     * @param  resouce $handle  file handle
     * @param  string  $content the value of cache
     * @param  bool    $rewirte whether rewrite the whole file
     * @return boolen
     */
    protected function _writeAndRelease($handle, $content, $rewirte = false)
    {
        if ($rewirte) {
            rewind($handle);
        }

        $result = fwrite($handle, $content);

        flock($handle, LOCK_UN);

        fclose($handle);

        return (bool) $result;
    }
}
