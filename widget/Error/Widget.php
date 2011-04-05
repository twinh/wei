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
 */

class Error_Widget extends Qwin_Widget_Abstract
{
    public function render($option = null)
    {
        // 自定义错误和异常处理
        //set_exception_handler(array($this, 'renderException'));
        //set_error_handler(array($this, 'renderError'));
    }

    /**
     * 显示异常信息
     *
     * @param Exception $e 异常对象
     * @todo xdebug
     * @todo view
     */
    public function renderException($e)
    {
        restore_error_handler();
        restore_exception_handler();
        
        // 清空之前输出的内容,再重新启动
        ob_end_clean();
        ob_start();
        
        $view = Qwin::call('-view');

        $content = null;
        $file = $e->getFile();
        $line = $e->getLine();
        if (Qwin::config('debug')) {
            $content =  'Throwed by ' . get_class($e) . ' in ' . $e->getFile() . ' on line ' . $e->getLine()
                . '<pre>' . $e->getTraceAsString() . '</pre>'
                . '<pre>' . $this->_getFileCode($file, $line) . '<pre>';
        }
        $view->error($e->getMessage(), null, $content);
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
    protected function _getFileCode($file, $line, $range = 20)
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
                $content .= $temp;
            } else {
                $content .= '<div style="color:red;">' . $temp . '</div>';
            }
        }

        unset($code);
        return $content;
    }
}
