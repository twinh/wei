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
     * The default log level
     * 
     * @var string
     */
    protected $defaultLevel = 'debug';
    
    /**
     *  and lowest level to write
     * 
     * @var string
     */
    protected $level = 'debug';
    
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
     * The dir to store log files
     * 
     * @var type 
     */
    protected $dir = 'log';
    
    /**
     * The log file name, formatted by strftime
     * 
     * @var string 
     */
    protected $fileFormat = '%Y%m%d.log';
    
    /**
     * The max file size for log file, default to 128mb
     * 
     * @var int 
     */
    protected $fileSize = 134217728;
        
    /**
     * The log levels and priorities
     *
     * @var array
     */
    protected $levels = array(
        'debug'     => 0,
        'info'      => 1,
        'warn'      => 2,
        'error'     => 3,
        'crit'      => 4,
        'alert'     => 5,
    );
    
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
        $level = isset($level) ? $level : $this->defaultLevel;

        $record = array(
            'level' => $level,
            'message' => $message,
            'time' => microtime(true),
        );

        // Check if the level would be handle
        if (isset($this->levels[$level])) {
            if ($this->levels[$level] < $this->levels[$this->level]) {
                return false;
            }
        }

        // Format the log message
        $content = str_replace(array(
            '%datetime%', '%channel%', '%level%', '%message%',
        ), array(
            date($this->dateFormat, $record['time']),
            $this->name,
            str_pad(strtoupper($record['level']), 5),
            $record['message'],
        ), $this->format);

        // Write the log message
        if (!$this->fileDetected || !$this->handle) {
            $this->close();
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
        
        $file = realpath($this->dir) . '/' . strftime($this->fileFormat);

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
     * Set custom log file
     * 
     * @param string $file The path of file
     * @return \Widget\Log
     * @throws \RuntimeException When unable to create directory
     */
    public function setFile($file)
    {
        if (!is_dir($dir = dirname($file)) && false === @mkdir($dir, 0777, true)) {
            throw new \RuntimeException('Unable to create directory ' . $dir);
        }
        
        $this->file = $file;
        $this->fileDetected = true;
        
        return $this;
    }
    
    /**
     * Set new directory for file
     *
     * @param string $dir
     * @return \Widget\Log
     */
    public function setDir($dir)
    {
        // Reset detectd state
        $this->fileDetected = false;
        $this->dir = $dir;

        return $this;
    }
    
    public function setFileFormat($format)
    {
        // Reset detectd state
        $this->fileDetected = false;
        $this->fileFormat = $format;
        
        return $this;
    }
    
    public function setFileSize($size)
    {
        // Reset detectd state
        $this->fileDetected = false;
        $this->fileSize = (int)$size;
        
        return $this;
    }
    
    /**
     * Log a debug level message
     *
     * @param string $message
     * @return bool
     */
    public function debug ($message)
    {
        return $this($message, 'debug');
    }

    /**
     * Log a info level message
     *
     * @param string $message
     * @return bool
     */
    public function info($message)
    {
        return $this($message, 'info');
    }

    /**
     * Log a warn level message
     *
     * @param string $message
     * @return bool
     */
    public function warn($message)
    {
        return $this($message, 'warning');
    }

    /**
     * Log a warn level message
     *
     * @param string $message
     * @return bool
     */
    public function error($message)
    {
        return $this($message, 'error');
    }

    public function crit($message)
    {
        return $this($message, 'crit');
    }
    
    /**
     * Log an error level message
     *
     * @param string $message
     * @return bool
     */
    public function alert($message)
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
    
    public function close()
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
