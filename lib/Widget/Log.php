<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget;

/**
 * The default logger for widget
 *
 * @package     Widget
 * @author      Twin Huang <twinh@yahoo.cn>
 * @todo        refator
 */
class Log extends WidgetProvider
{   
    /**
     * Log options
     *
     * @var array
     *
     *      -- level        string      default level for "call" method and lowest level to write
     *
     *      -- format       string      logs format, words start with "$" would be replace to the
     *                                  same variable, now only have "$time", "$level" and "$message"
     *
     *      -- timeFormat   string      time format in logs, formatted by strftime
     *
     *      -- save         callback    handle to save logs
     *
     *      -- file         string      logs file name, if specify this argument, option "fileDir"
     *                                  and "fileFormat" would be ignored
     *
     *      -- fileDir      string      dir to store logs file
     *
     *      -- fileFormat   string      logs file name, formatted by strftime
     *
     *      -- fileSize     int         max file size for logs file
     */
    public $options = array(
        'level' => 'debug',
        'format' => '$time $level - $message',
        'timeFormat' => '%Y-%m-%d %H:%M:%S',
        'save' => null,
        'file' => null,
        'fileDir' => './logs',
        'fileFormat' => '%Y%m%d.log',
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
    protected $_data = array();

    /**
     * current working directory for getFileOption method
     *
     * @var string
     */
    protected $_cwd;

    /**
     * whether log file's exact path has been detected
     * when set fileDir, fileFormat or fileSize options, log file should be detected again
     *
     * @var bool
     */
    protected $_fileDetected = false;

    public function __construct($options = null)
    {
        $options = (array)$options + $this->options;
        $this->options = &$options;

        $this->_cwd = getcwd();

        $this->option($options);

        // save log file when shutdown
        $that = $this;
        $this->on('shutdown', function() use($that) {
            $that->save();
        });
    }

    /**
     * Log a default level message
     *
     * @param string $message
     * @return Qwin_Log
     */
    public function __invoke($message, $level = null)
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
     * Set save option
     *
     * @param callback $callback
     * @return Qwin_Log
     */
    public function setSaveOption($callback)
    {
        if (!$callback) {
            $this->options['save'] = array($this, 'save');
        } elseif (is_callable($callback)) {
            $this->options['save'] = $callback;
        } else {
            return $this->exception('Invalid callback for save option');
        }
        return $this;
    }

    /**
     * Get log file
     *
     * @return string
     */
    public function getFileOption()
    {
        if ($this->_fileDetected) {
            return $this->options['file'];
        }

        // some time, getFileOption is called in register_shutdown_function
        // and the working directory is changed, we should change it back to the website directory
        $cwd = getcwd();
        chdir($this->_cwd);

        $options = &$this->options;

        $file = &$options['file'];

        if (!is_dir($options['fileDir'])) {
            mkdir($options['fileDir'], 0777, true);
        }
        // use absolute path for file_put_contents in register_shutdown_function
        $file = realpath($options['fileDir']) . '/' . strftime($options['fileFormat']);

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

        chdir($cwd);

        $this->_fileDetected = true;

        return $file;
    }

    public function setFileOption($file)
    {
        // reset detected state
        if (!$file) {
            $this->_fileDetected = false;
            return $this;
        }

        $dir = dirname($file);
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        $this->options['file'] = $file;
        $this->_fileDetected = true;

        return $this;
    }

    /**
     * Set file directory
     *
     * @param string $dir
     * @return Qwin_Log
     */
    public function setFileDirOption($dir)
    {
        // reset detectd state
        $this->_fileDetected = false;
        $this->options['fileDir'] = $dir;
        return $this;
    }

    /**
     * Set file name format
     *
     * @param string $format
     * @return Qwin_Log
     */
    public function setFileFormatOption($format)
    {
        // reset detectd state
        $this->_fileDetected = false;
        $this->options['fileFormat'] = $format;
        return $this;
    }

    /**
     * Set file size
     *
     * @param int $size
     * @return Qwin_Log
     */
    public function setFileSizeOption($size)
    {
        // reset detectd state
        $this->_fileDetected = false;
        $this->options['fileSize'] = (int)$size;
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
        return $this->__invoke($message, 'trace');
    }

    /**
     * Log a debug level message
     *
     * @param string $message
     * @return Qwin_Log
     */
    public function debug($message)
    {
        return $this->__invoke($message, 'debug');
    }

    /**
     * Log a info level message
     *
     * @param string $message
     * @return Qwin_Log
     */
    public function info($message)
    {
        return $this->__invoke($message, 'info');
    }

    /**
     * Log a warn level message
     *
     * @param string $message
     * @return Qwin_Log
     */
    public function warn($message)
    {
        return $this->__invoke($message, 'warn');
    }

    /**
     * Log an error level message
     *
     * @param string $message
     * @return Qwin_Log
     */
    public function error($message)
    {
        return $this->__invoke($message, 'error');
    }

    /**
     * Log a fatal level message
     *
     * @param string $message
     * @return Qwin_Log
     */
    public function fatal($message)
    {
        return $this->__invoke($message, 'fatal');
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
                strftime($this->options['timeFormat'], $data['time']),
                str_pad(strtoupper($data['level']), 5),
                $data['message'],
            ), $this->options['format']) . PHP_EOL;
        }

        file_put_contents($this->getFileOption(), $content, FILE_APPEND|LOCK_EX);

        // clear logs
        $this->_data = array();

        return $this;
    }

    public function clean()
    {
        $dir = dirname($this->getFileOption());
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
}
