<?php
/**
 * Widget
 *
 * Copyright (c) 2008-2011 Twin Huang. All rights reserved.
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
 * @since       2011-04-02 09:29:07
 * @todo        是否与视图微件耦合?是否应该整合为一个
 */

class Error_Widget extends Qwin_Widget_Abstract
{   
    protected $_defaults = array(
        'exception' => true,
        //'error' => false,
    );
    
    public function __construct(array $options = array())
    {
        parent::__construct($options);
        if ($this->_options['exception']) {
            //set_exception_handler(array($this, 'render'));
        }
    }
    
    /**
     * 显示异常信息
     *
     * @param Exception $e 异常对象
     * @todo xdebug
     * @todo view
     */
    public function render($e = null)
    {
        restore_exception_handler();
        
        // 清空之前输出的内容,再重新启动
        $output = ob_get_contents();
        '' != $output && ob_end_clean();
        ob_start();

        $content = null;
        $file = $e->getFile();
        $line = $e->getLine();
        if (Qwin::config('debug')) {
            $content =  'Throwed by ' . get_class($e) . ' in ' . $file . ' on line ' . $line
                . '<pre>' . $e->getTraceAsString() . '</pre>'
                . '<pre>' . $this->getFileCode($file, $line) . '<pre>';
        }

        return $this->_view->displayInfo(array(
            'icon'      => 'delete',
            'title'     => $e->getMessage(),
            'url'       => null,
            'content'   => $content,
            'exception' => $e,
        ));
    }

    /**
     * 显示错误信息
     *
     * @param int $errno 错误级别
     * @param string $errstr 错误信息
     * @param string $errfile 错误文件
     * @param int $errline 错误行号
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
                $content .= htmlspecialchars($temp);
            } else {
                $content .= '<span class="ui-state-error">' . htmlspecialchars($temp) . '</span>';
            }
        }

        unset($code);
        return $content;
    }
    
    /**
     * 构造运行记录
     * 
     * @param array $traces 运行记录,一般由debug_backtrace取得
     * @param int $offset 剔除运行记录数
     * @return string 
     */
    public function getTraceString($traces, $offset = 0)
    {
        for($i = -1; $i < $offset; $i++) {
            $first = array_shift($traces);
        }
        if (isset($first['class'])) {
            $calledBy = $first['class'] . $first['type'] . $first['function'];
        } else {
            $calledBy = $first['function'];
        }
        $msg = 'Called by '. $calledBy . ' in ' . $first['file'] . ' on line ' . $first['line'] . PHP_EOL . PHP_EOL;
        foreach ($traces as $i => $trace) {
            $msg .= '#' . $i . ' ';
            if (isset($trace['file'])) {
                $msg .= sprintf('%s(%s)', $trace['file'], $trace['line']);
            } else {
                $msg .= '[internal function]';
            }
            if (isset($trace['class'])) {
                $msg .= ': ' . $trace['class'] . $trace['type'] . $trace['function'];
            } else {
                $msg .= ': ' . $trace['function'];
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
            $msg .= '(' . implode(', ', $args) . ')';
            $msg .= PHP_EOL;
        }
        $msg .= '#' . ++$i . ' {main}' . PHP_EOL . PHP_EOL;
        return $msg;
    }
}
