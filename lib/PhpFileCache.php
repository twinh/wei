<?php

/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2020 Twin Huang
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
    protected function readAndVerify($handle, $file)
    {
        $content = $this->getContent($file);

        // Check if content is valid
        if ($content && is_array($content) && time() < $content[0]) {
            return $content;
        } else {
            return false;
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function getContent($file)
    {
        return require $file;
    }

    /**
     * {@inheritdoc}
     */
    protected function prepareContent($content, $expire)
    {
        $time = time();
        $content = var_export([$expire ? $time + $expire : 2147483647, $content], true);
        return "<?php\n\nreturn " . $content . ';';
    }
}
