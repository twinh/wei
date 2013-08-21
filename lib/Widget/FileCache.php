<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Widget;

/**
 * A cache widget that stored data in files
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class FileCache extends BaseCache
{
    /**
     * The cache directory
     *
     * @var string
     */
    protected $dir = 'cache';

    /**
     * Illegal chars as the name of cache, would be replaced to "_"
     *
     * @var array
     */
    protected $illegalChars = array(
        '\\', '/', ':', '*', '?', '"', '<', '>', '|', "\r", "\n"
    );

    /**
     * The cache file extension
     *
     * @var string
     */
    protected $ext = 'cache';

    /**
     * Constructor
     *
     * @param array $options
     */
    public function __construct(array $options = array())
    {
        parent::__construct($options + array(
            'dir' => $this->dir
        ));
    }

    /**
     * {@inheritdoc}
     */
    protected function doGet($key)
    {
        $file = $this->getFile($key, false);

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
     * {@inheritdoc}
     */
    protected function doSet($key, $value, $expire = 0)
    {
        $file = $this->getFile($key, false);

        $content = $this->prepareContent($value, $expire);

        return (bool) file_put_contents($file, $content, LOCK_EX);
    }

    /**
     * {@inheritdoc}
     */
    protected function doRemove($key)
    {
        $file = $this->getFile($key, false);

        if (is_file($file)) {
            return unlink($file);
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    protected function doExists($key)
    {
        $file = $this->getFile($key, false);

        if (!is_file($file)) {
            return false;
        }

        $content = @unserialize(file_get_contents($file));
        if ($content && is_array($content) && time() < $content[0]) {
            return true;
        } else {
            $this->remove($key);
            return false;
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function doAdd($key, $value, $expire = 0)
    {
        $file = $this->getFile($key, false);

        if (!is_file($file)) {
            // Open and try to lock file immediately
            if (!$handle = $this->openAndLock($file, 'wb', LOCK_EX | LOCK_NB)) {
                return false;
            }

            $content = $this->prepareContent($value, $expire);

            return $this->writeAndRelease($handle, $content);
        } else {
            // Open file for reading and rewriting
            if (!$handle = $this->openAndLock($file, 'r+b', LOCK_EX)) {
                return false;
            }

            // The cache is not expired
            if ($this->readAndVerify($handle, $file)) {
                fclose($handle);
                return false;
            }

            $content = $this->prepareContent($value, $expire);

            return $this->writeAndRelease($handle, $content, true);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function doReplace($key, $value, $expire = 0)
    {
        $file = $this->getFile($key, false);

        if (!is_file($file)) {
            return false;
        }

        // Open file for reading and rewriting
        if (!$handle = $this->openAndLock($file, 'r+b', LOCK_EX)) {
            return false;
        }

        if (!$this->readAndVerify($handle, $file)) {
            fclose($handle);
            return false;
        }

        $content = $this->prepareContent($value, $expire);

        return $this->writeAndRelease($handle, $content, true);
    }

    /**
     * {@inheritdoc}
     */
    protected function doIncr($key, $offset = 1)
    {
        $file = $this->getFile($key, false);

        if (!is_file($file)) {
            return $this->doSet($key, $offset) ? $offset : false;
        }

        // Open file for reading and rewriting
        if (!$handle = $this->openAndLock($file, 'r+b', LOCK_EX)) {
            return false;
        }

        // Prepare file content
        if (!$content = $this->readAndVerify($handle, $file)) {
            $content = $this->prepareContent($offset, 0);
            $result = $offset;
        } else {
            $result = $content[1] += $offset;
            $content = serialize($content);
        }

        // Rewrite content
        return $this->writeAndRelease($handle, $content, true) ? $result : false;
    }


    /**
     * {@inheritdoc}
     */
    public function clear()
    {
        $result = true;

        foreach (glob($this->dir . '/' . '*.' . $this->ext) as $file) {
            $result = $result && @unlink($file);
        }

        return $result;
    }

    /**
     * Get cache file by key
     *
     * @param  string $key
     * @param bool $withPrefix
     * @return string
     */
    public function getFile($key, $withPrefix = true)
    {
        $withPrefix && $key = $this->getKeyWithPrefix($key);
        $key = str_replace($this->illegalChars, '_', $key);

        return $this->dir . '/' . $key . '.' . $this->ext;
    }

    /**
     * Set the cache directory
     *
     * @param string $dir
     * @return FileCache
     * @throws \RuntimeException When failed to create the cache directory
     */
    public function setDir($dir)
    {
        if (!is_dir($dir)) {
            if (true !== @mkdir($dir, 0777, true)) {
                throw new \RuntimeException(sprintf('Failed to create directory: "%s"', $dir));
            }
        }

        $this->dir = $dir;

        return $this;
    }

    /**
     * Returns the cache directory
     *
     * @return string
     */
    public function getDir()
    {
        return $this->dir;
    }

    /**
     * Open and lock file
     *
     * @param  string         $file      file path
     * @param  string         $mode      open mode
     * @param  int            $operation lock operation
     * @return false false or file handle
     */
    protected function openAndLock($file, $mode, $operation)
    {
        if (!$handle = fopen($file, $mode)) {
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
    protected function readAndVerify($handle, $file)
    {
        // Read all content
        $content = fread($handle, filesize($file));
        $content = @unserialize($content);

        // Check if content is valid
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
    protected function prepareContent($content, $expire)
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
     * @return boolean
     */
    protected function writeAndRelease($handle, $content, $rewirte = false)
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
