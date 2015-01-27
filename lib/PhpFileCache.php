<?php
/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2015 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

/**
 * A cache service that stored data as PHP variables in files
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class PhpFileCache extends FileCache
{
    /**
     * {@inheritdoc}
     */
    protected $ext = 'php';

    /**
     * {@inheritdoc}
     */
    public function get($key, $expire = null, $fn = null)
    {
        if (!is_file($file = $this->getFile($key))) {
            $result = false;
        } else {
            $content = require $file;
            if ($content && is_array($content) && time() < $content[0]) {
                $result = $content[1];
            } else {
                $this->remove($key);
                $result = false;
            }
        }
        return $this->processGetResult($key, $result, $expire, $fn);
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
        $time = time();
        $datetime = date('Y-m-d H:i:s', $time);
        $content = var_export(array($expire ? $time + $expire : 2147483647, $content), true);
        return "<?php \n// The file was generated at $datetime \nreturn " . $content . ';';
    }
}