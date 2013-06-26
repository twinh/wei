<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Widget;

/**
 * A widget that flushes the content to browser immediately
 *
 * @author      Twin Huang <twinhuang@qq.com>
 * @link        http://php.net/manual/en/function.flush.php
 */
class Flush extends Response
{
    /**
     * Disable compress and implicit flush
     */
    public function prepare()
    {
        if (function_exists('apache_setenv')) {
            apache_setenv('no-gzip', '1');
        }

        /**
         * Disable zlib to compress output
         *
         * @link http://www.php.net/manual/en/zlib.configuration.php
         */
        if (extension_loaded('zlib') && !headers_sent()) {
            ini_set('zlib.output_compression', '0');
        }

        /**
         * Turn implicit flush on
         *
         * @link http://www.php.net/manual/en/function.ob-implicit-flush.php
         */
        ob_implicit_flush();
    }

    /**
     * Send response content
     *
     * @param string $content
     * @param int $status
     * @return Flush
     */
    public function send($content = null, $status = null)
    {
        $this->prepare();

        parent::send($content, $status);

        /**
         * Send blank characters for output_buffering
         *
         * @link http://www.php.net/manual/en/outcontrol.configuration.php
         */
        if ($length = ini_get('output_buffering')) {
            echo str_pad('', $length);
        }

        while (ob_get_level()) {
            ob_end_flush();
        }

        return $this;
    }
}
