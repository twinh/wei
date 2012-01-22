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
 * Log
 * 
 * @package     Qwin
 * @subpackage  Widget
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @author      Twin Huang <twinh@yahoo.cn>
 * @since       2011-12-21 15:47:18
 * @depends     cache
 * @todo        wirte widget
 */
class Qwin_Log extends Qwin_Widget
{
    /**
     * Log options
     * 
     * @var array
     * @todo logs format
     */
    public $options = array(
        'level' => 'debug',
        'format' => '$time $level - $message',
        'timeFormat' => 'Y-m-d H:i:s',
        'save' => null,
        'file' => null,
        'fileDir' => null,
        'fileFormat' => 'Ymd.\l\o\g',
        'fileSize' => 134217728, // 128mb
    );
    
    /**
     * Log levels and priorities
     * 
     * @var array
     */
    protected $_levels = array(
        'trace' => 0,
        'debug' => 1,
        'info'  => 2,
        'warn'  => 3,
        'error' => 4,
        'fatal' => 5,
    );
    
    /**
     * log data
     * 
     * @var array
     */
    private $_data = array();

    public function __construct($options = null)
    {
        $options = (array)$options + $this->options;
        
        if (!$options['save']) {
            $options['save'] = array($this, 'save');
        }
        register_shutdown_function($options['save']);
        
        $file = &$options['file'];
        if (!$file) {
            if (!$options['fileDir']) {
                $options['fileDir'] = $this->cache->option('dir') . '/logs';
                if (!is_dir($options['fileDir'])) {
                    mkdir($options['fileDir']);
                }
            }
            // use absolute path so that file_put_contents would works
            $file = realpath($options['fileDir']) . '/' . date($options['fileFormat']);
        }
        
        if ($options['fileSize']) {
            $firstFile = $file;
        
            $files = glob($file . '*', GLOB_NOSORT);

            if (1 < count($files)) {
                natsort($files);
                $file = array_pop($files);
            } 

            if (is_file($file) && $options['fileSize'] < filesize($file)) {
                $ext = pathinfo($file, PATHINFO_EXTENSION);
                if (is_numeric($ext)) {
                    $file = $firstFile . '.' . ($ext + 1);
                } else {
                    $file = $firstFile . '.1';
                }
            }
        }

        $this->options = &$options;
    }
    
    /**
     * Log a default level message
     * 
     * @param string $message
     * @return Qwin_Log 
     */
    public function call($message, $level = null)
    {
        $level = isset($level) ? $level : $this->options['level'];

        // TODO add class method file line invoker category ?
        $this->_data[] = array(
            'level' => $level, 
            'message' => $message, 
            'time' => microtime(true),
        );

        return $this;
    }
    
    /**
     * Log a trace level message
     * 
     * @param string $message
     * @return Qwin_Log 
     */
    public function trace($message)
    {
        return $this->call($message, 'trace');
    }
    
    /**
     * Log a debug level message
     * 
     * @param string $message
     * @return Qwin_Log 
     */
    public function debug($message)
    {
        return $this->call($message, 'debug');
    }
    
    /**
     * Log a info level message
     * 
     * @param string $message
     * @return Qwin_Log 
     */
    public function info($message)
    {
        return $this->call($message, 'info');
    }
    
    /**
     * Log a warn level message
     * 
     * @param string $message
     * @return Qwin_Log 
     */
    public function warn($message)
    {
        return $this->call($message, 'warn');
    }
    
    /**
     * Log an error level message
     * 
     * @param string $message
     * @return Qwin_Log 
     */
    public function error($message)
    {
        return $this->call($message, 'error');
    }
    
    /**
     * Log a fatal level message
     * 
     * @param string $message
     * @return Qwin_Log
     */
    public function fatal($message)
    {
        return $this->call($message, 'fatal');
    }
    
    /**
     * Default method to save logs
     * 
     * @return Qwin_Log 
     */
    public function save()
    {
        $content = '';
        foreach ($this->_data as $data) {
            if (isset($this->_levels[$data['level']])) {
                if ($this->_levels[$data['level']] < $this->_levels[$this->options['level']]) {
                    continue;
                }
            }
            $content .= str_replace(array(
                '$time', '$level', '$message',
            ), array(
                date($this->options['timeFormat'], $data['time']),
                strtoupper($data['level']),
                $data['message'],
            ), $this->options['format']) . PHP_EOL;
        }
        
        file_put_contents($this->options['file'], $content, FILE_APPEND);
        
        return $this;
    }
}