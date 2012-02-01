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
 * Error
 *
 * @package     Qwin
 * @subpackage  Application
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @author      Twin Huang <twinh@yahoo.cn>
 * @since       2011-04-02 09:29:07
 */
class Qwin_Error extends Qwin_Widget
{
    public $options = array(
        'code' => 500,
        'message' => 'Server busy, please try again later',
        'exception' => true,
        'error' => true,
        'exit' => true,
        'clean' => true,
    );

    public function __construct(array $options = array())
    {
        parent::__construct($options);
        if ($this->options['exception']) {
            set_exception_handler(array($this, 'call'));
        }
        if ($this->options['error']) {
            set_error_handler(array($this, 'renderError'));
        }
    }

    /**
     * Show error message
     *
     * @param mixed $message
     * @param mixed $code
     * @param array $options
     */
    public function call($message, $code = 500, array $options = array())
    {
        try {
            if ($this->options['exception']) {
                restore_exception_handler();
            }
            if ($this->options['error']) {
                restore_error_handler();
            }

            if ($message instanceof Exception) {
                if (is_array($code)) {
                    $options = $code;
                }
                $e = $message;
                $code = $e->getCode();
                $message = $e->getMessage();
                $file = $e->getFile();
                $line = $e->getLine();
                $class = get_class($e);
                $trace = $e->getTraceAsString();
            } else {
                if (is_array($message)) {
                    $options = $message;
                    $message = isset($options['message']) ? $options['message'] : $this->options['message'];
                } else {
                    $message = (string)$message;
                }

                $offset = 5;
                $traces = debug_backtrace();
                $file = $traces[$offset-1]['file'];
                $line = $traces[$offset-1]['line'];
                $class = 'Qwin_Error';
                $traces = array_slice($traces, $offset);
                $trace = $this->getTraceString($traces);
            }


            // merge options
            $options = array(
                'code' => $code,
                'message' => $message,
            ) + $options + $this->options;
            $this->options = &$options;

            // clean up output
            if ($options['clean'] && ob_get_status()) {
                $output = ob_get_contents();
                $output && ob_end_clean();
                ob_start();
            }

            // TODO header for ajax request

            // Title & Message
            $code = $code ? $code . ': ' : '';

            // Call Stack
            $stackInfo = sprintf('Raised by %s in %s on line %s', $class, $file, $line);
            $trace = htmlspecialchars($trace, ENT_QUOTES);

            // File Infomation
            $mtime = strftime('%c', filemtime($file));
            $fileInfo = $this->getFileCode($file, $line);

            // System Information
            $requestMethod = $_SERVER['REQUEST_METHOD'];

            $requestUrl = htmlspecialchars(urldecode($_SERVER['REQUEST_URI']), ENT_QUOTES);;

            $serverTime = strftime('%c');

            $includePaths = explode(PATH_SEPARATOR, get_include_path());
            foreach ($includePaths as $key => $value) {
                $includePaths[$key] = realpath($value);
            }
            $includePath = implode('<br />', $includePaths);

            // Request Information
            $get = $this->getGet();

            $post = $this->getPost();

            $cookie = $this->getCookie();

            $session = $this->getSession();

            // Server Environment
            $server = $this->getServer();

            $this->log->fatal($code . $message . ' ' . $stackInfo);

            // require view file
            require dirname(__FILE__) . '/views/error.php';

            // exit to prevent other output
            if ($options['exit']) {
                exit();
            } else {
                if ($this->options['exception']) {
                    set_exception_handler(array($this, 'call'));
                }
                if ($this->options['error']) {
                    set_error_handler(array($this, 'renderError'));
                }
            }

        // dispaly basic error message for exception in exception handler
        } catch(Exception $e) {
            echo sprintf('<p>%s: %s in %s on line %s</p>', get_class($e), $e->getMessage(), $e->getFile(), $e->getCode());
            echo '<pre>' . $e->getTraceAsString() . '</pre>';
        }
    }

    /**
     * Error handler
     *
     * @param int $errno
     * @param string $errstr
     * @param string $errfile
     * @param int $errline
     */
    public function renderError($errno, $errstr, $errfile, $errline)
    {
        restore_error_handler();
        throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
    }

    /**
     * 获取文件指定行号周围的代码
     *
     * @param string $file 文件路径
     * @param int $line 文化行号
     * @param int $range 获取的总行数
     * @return string 文件代码
     */
    public function getFileCode($file, $line, $range = 20)
    {
        if (false == ($code = file($file))) {
            return null;
        }

        $half = (int)($range / 2);

        // 开始行
        $start = $line - $half;
        0 > $start && $start = 0;

        // 结束行
        $total = count($code);
        $end = $line + $half;
        $total < $end && $end = $total;

        // 调整file获取的文件行数与Exception->getLine相差一行的问题
        array_unshift($code, null);
        $content = '';
        for($i = $start; $i < $end; $i++) {
            $temp = str_pad($i, 4, 0, STR_PAD_LEFT) . ':' . $code[$i];
            if ($line != $i) {
                $content .= htmlspecialchars($temp, ENT_QUOTES);
            } else {
                $content .= '<span class="ui-state-error">' . htmlspecialchars($temp) . '</span>';
            }
        }

        unset($code);
        return $content;
    }

    /**
     * Get trace string like Exception::getTraceAsString
     *
     * @param array $traces usally get from debug_backtrace()
     * @return string
     */
    public function getTraceString($traces)
    {
        $str = '';
        foreach ($traces as $i => $trace) {
            $str .= '#' . $i . ' ';
            if (isset($trace['file'])) {
                $str .= sprintf('%s(%s)', $trace['file'], $trace['line']);
            } else {
                $str .= '[internal function]';
            }
            if (isset($trace['class'])) {
                $str .= ': ' . $trace['class'] . $trace['type'] . $trace['function'];
            } else {
                $str .= ': ' . $trace['function'];
            }

            $args = array();
            foreach ($trace['args'] as $arg) {
                if (is_object($arg)) {
                    $args[] = 'Object(' . get_class($arg) . ')';
                } elseif (is_string($arg)) {
                    $args[] = "'{$arg}'";
                } elseif (is_bool($arg)) {
                    if (true == $arg) {
                        $args[] = 'true';
                    } else {
                        $args[] = 'false';
                    }
                } else {
                    $args[] = (string)$arg;
                }
            }
            $str .= '(' . implode(', ', $args) . ')' . PHP_EOL;
        }
        $str .= '#' . ++$i . ' {main}' . PHP_EOL . PHP_EOL;
        return $str;
    }

    /**
     * Get readable server information form $_SERVER for html output
     *
     * @return array
     */
    public function getServer()
    {
        $server = array();
        foreach ($_SERVER as $key => $value) {
            if ('PATH' == $key) {
                $paths = explode(PATH_SEPARATOR, $value);
                foreach ($paths as &$path) {
                    $path = htmlspecialchars(realpath($path), ENT_QUOTES);
                }
                $value = implode('<br />', $paths);
                $server[$key] = $value;
                continue;
            } elseif ('REQUEST_TIME' == $key) {
                $server[$key] = $value . '&nbsp;<em>(' . strftime('%c', $value) . ')</em>';
                continue;
            } elseif ('QUERY_STRING' == $key || 'REQUEST_URI' == $key) {
                $value = urldecode($value);
            } elseif (is_array($value)) {
                $server[$key] = '<pre>' . htmlspecialchars(var_export($value, true), ENT_QUOTES) . '</pre>';
                continue;
            }
            $server[$key] = htmlspecialchars((string)$value, ENT_QUOTES);
        }
        return $server;
    }

    /**
     * Get reqeust information form $_GET for html output
     *
     * @return array
     */
    public function getGet()
    {
        $get = array();
        foreach ($_GET as $key => $value) {
            if (is_array($value)) {
                $value = var_export($value, true);
            }
            $get[$key] = htmlspecialchars($value, ENT_QUOTES);
        }
        return $get;
    }

    /**
     * Get reqeust information form $_POST for html output
     *
     * @return array
     */
    public function getPost()
    {
        $post = array();
        foreach ($_POST as $key => $value) {
            if (is_array($value)) {
                $value = var_export($value, true);
            }
            $post[$key] = htmlspecialchars($value, ENT_QUOTES);
        }
        return $post;
    }

    /**
     * Get reqeust information form $_COOKIE for html output
     *
     * @return array
     */
    public function getCookie()
    {
        foreach ($_COOKIE as $key => $value) {
            $cookie[$key] = htmlspecialchars($value, ENT_QUOTES);
        }
        return $cookie;
    }

    /**
     * Get session information form $_SESSION for html output
     *
     * @return array
     * @todo how about session not enable
     */
    public function getSession()
    {
        // Session Information
        $session = array();

        // TODO
        $this->session;

        foreach ($_SESSION as $key => $value) {
            if (is_array($value)) {
                $value = var_export($value, true);
            }
            $session[$key] = htmlspecialchars($value, ENT_QUOTES);
        }
    }
}
