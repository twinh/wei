<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget;

/**
 * The default logger for widget, which is base on the Monolog!
 *
 * @package     Widget
 * @author      Twin Huang <twinh@yahoo.cn>
 * @link        https://github.com/Seldaek/monolog
 */
class Log extends WidgetProvider
{
    /**
     * The name of channel
     *  
     * @var string
     */
    protected $name = 'widget';

    /**
     * The default level for log record which level is not specified
     * 
     * @var string
     */
    protected $level = 'debug';
    
    /**
     * The lowest level to be handled
     * 
     * @var string
     */
    protected $handledLevel = 'debug';
    
    /**
     * The log levels and priorities
     *
     * @var array
     */
    protected $levels = array(
        'debug'     => 0,
        'info'      => 1,
        'warning'   => 2,
        'error'     => 3,
        'critical'  => 4,
        'alert'     => 5,
    );

    /**
     * The format for log message
     * 
     * @var string 
     */
    protected $format = "[%datetime%] %channel%.%level%: %message%\n";
    
    /**
     * The date format for log message
     * 
     * @var string 
     */
    protected $dateFormat = 'Y-m-d H:i:s';
    
    /**
     * The log file name, if specify this parameter, the "dir" and "fileFormat" 
     * parameters would be ignored
     * 
     * @var null|string 
     */
    protected $file = null;
    
    /**
     * The directory to store log files
     * 
     * @var type 
     */
    protected $dir = 'log';
    
    /**
     * The log file name, formatted by date
     * 
     * @var string 
     */
    protected $fileFormat = 'Ymd.\l\o\g';
    
    /**
     * The max file size for log file, default to 128mb, set 0 to ignore this 
     * property
     * 
     * @var int 
     */
    protected $fileSize = 134217728;
    
    /**
     * The log file handle
     * 
     * @var resource|null 
     */
    protected $handle;

    /**
     * Whether log file's exact path has been detected, when set dir, fileFormat
     * or fileSize options, log file should be detected again
     *
     * @var bool
     */
    protected $fileDetected = false;

    /**
     * Log a default level message
     *
     * @param string $message
     * @return bool whether the log record has been handle
     */
    public function __invoke($message, $level = null)
    {
        $level = isset($level) ? $level : $this->level;

        $record = array(
            'level' => $level,
            'message' => $message,
            'time' => microtime(true),
        );

        // Check if the level would be handle
        if (isset($this->levels[$level])) {
            if ($this->levels[$level] < $this->levels[$this->handledLevel]) {
                return false;
            }
        }

        // Format the log message
        $content = str_replace(array(
            '%datetime%', '%channel%', '%level%', '%message%',
        ), array(
            date($this->dateFormat, $record['time']),
            $this->name,
            strtoupper($record['level']),
            $record['message'],
        ), $this->format);
        
        // Write the log message
        if (!$this->handle) {
            $this->handle = fopen($this->getFile(), 'a');
        }
        fwrite($this->handle, $content);
        
        return true;
    }

    /**
     * Get log file
     *
     * @return string
     */
    public function getFile()
    {
        if ($this->fileDetected) {
            return $this->file;
        }

        $this->handle = null;
        $file = &$this->file;

        if (!is_dir($this->dir) && false === @mkdir($this->dir, 0777, true)) {
            throw new \RuntimeException('Unable to create directory ' . $this->dir);
        }
        
        $file = realpath($this->dir) . '/' . date($this->fileFormat);

        if ($this->fileSize) {
            $firstFile = $file;

            $files = glob($file . '*', GLOB_NOSORT);

            if (1 < count($files)) {
                natsort($files);
                $file = array_pop($files);
            }

            if (is_file($file) && $this->fileSize < filesize($file)) {
                $ext = pathinfo($file, PATHINFO_EXTENSION);
                if (is_numeric($ext)) {
                    $file = $firstFile . '.' . ($ext + 1);
                } else {
                    $file = $firstFile . '.1';
                }
            }
        }

        $this->fileDetected = true;

        return $file;
    }
    
    /**
     * Set default log level
     * 
     * @param string $level
     * @return \Widget\Log
     */
    public function setLevel($level)
    {
        $this->level = $level;
        
        return $this;
    }
    
    public function setHandledLevel($handledLevel)
    {
        $this->handledLevel = $handledLevel;
        
        return $this;
    }
    
    /**
     * Adds a log record at DEBUG level
     *
     * @param string $message
     * @return bool
     */
    public function addDebug($message)
    {
        return $this($message, 'debug');
    }

    /**
     * Adds a log record at INFO level
     *
     * @param string $message
     * @return bool
     */
    public function addInfo($message)
    {
        return $this($message, 'info');
    }

    /**
     * Adds a log record at WARNING level
     *
     * @param string $message
     * @return bool
     */
    public function addWarning($message)
    {
        return $this($message, 'warning');
    }

    /**
     * Adds a log record at ERROR level
     *
     * @param string $message
     * @return bool
     */
    public function addError($message)
    {
        return $this($message, 'error');
    }

    /**
     * Adds a log record at CRITICAL level
     *
     * @param string $message
     * @return bool
     */
    public function addCritical($message)
    {
        return $this($message, 'critical');
    }
    
    /**
     * Adds a log record at ALERT level
     *
     * @param string $message
     * @return bool
     */
    public function addAlert($message)
    {
        return $this($message, 'alert');
    }

    /**
     * Clear up all log file
     * 
     * @return \Widget\Log
     */
    public function clean()
    {
        // Make sure the handle is close
        $this->close();
        
        $dir = dirname($this->getFile());
        if (is_dir($dir)) {
            $files = scandir($dir);
            foreach ($files as $file) {
                if ('.' != $file && '..' != $file) {
                    $file = $dir . DIRECTORY_SEPARATOR .  $file;
                    if (is_file($file)) {
                        unlink($file);
                    }
                }
            }
        }
        return $this;
    }
    
    protected function close()
    {
        if (is_resource($this->handle)) {
            fclose($this->handle);
        }
        $this->handle = null;
    }
    
    public function __destruct()
    {
        $this->close();
    }
}
