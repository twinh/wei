<?php
/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

/**
 * A simple logger service, which is base on the Monolog
 *
 * @author      Twin Huang <twinhuang@qq.com>
 * @link        https://github.com/Seldaek/monolog
 */
class Logger extends Base
{
    /**
     * The name of channel
     *
     * @var string
     */
    protected $name = 'wei';

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
        'debug'     => 100,
        'info'      => 200,
        'notice'    => 250,
        'warning'   => 300,
        'error'     => 400,
        'critical'  => 500,
        'alert'     => 550,
        'emergency' => 600
    );

    /**
     * The format for log message
     *
     * @var string
     */
    protected $format = "[%datetime%] %level%: %message%\n";

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
     * @var string
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
     * Logs with an arbitrary level
     *
     * @param mixed $level
     * @param string $message
     * @param array $context
     * @return bool Whether the log record has been handled
     */
    public function log($level, $message, array $context = array())
    {
        $level = isset($this->levels[$level]) ? $level : $this->level;

        // Check if the level would be handled
        if (isset($this->levels[$level])) {
            if ($this->levels[$level] < $this->levels[$this->handledLevel]) {
                return false;
            }
        }

        return $this->writeLog($level, $message, $context);
    }

    /**
     * Write the log message
     *
     * @param string $level
     * @param string $message
     * @param array $context
     * @return bool
     */
    protected function writeLog($level, $message, $context)
    {
        if (!$this->handle) {
            $this->handle = fopen($this->getFile(), 'a');
        }
        $content = $this->formatLog($level, $message, $context);
        return (bool)fwrite($this->handle, $content);
    }

    /**
     * Format the log message
     *
     * @param string $level
     * @param string $message
     * @param array $context
     * @return string
     */
    protected function formatLog($level, $message, array $context = array())
    {
        if (is_array($message)) {
            $message = print_r($message, true);
        } else {
            $message = (string)$message;
        }

        $content = str_replace(array(
            '%datetime%', '%channel%', '%level%', '%message%',
        ), array(
            date($this->dateFormat, microtime(true)),
            $this->name,
            strtoupper($level),
            $message,
        ), $this->format);
        return $content;
    }

    /**
     * Logs with an arbitrary level
     *
     * @param mixed $level
     * @param string $message
     * @param array $context
     * @return bool
     */
    public function __invoke($level, $message, array $context = array())
    {
        return $this->log($level, $message, $context);
    }

    /**
     * System is unusable.
     *
     * @param string $message
     * @param array $context
     * @return bool
     */
    public function emergency($message, array $context = array())
    {
        return $this('emergency', $message, $context);
    }

    /**
     * Action must be taken immediately.
     *
     * Example: Entire website down, database unavailable, etc. This should
     * trigger the SMS alerts and wake you up.
     *
     * @param string $message
     * @param array $context
     * @return bool
     */
    public function alert($message, array $context = array())
    {
        return $this->log('alert', $message, $context);
    }

    /**
     * Critical conditions.
     *
     * Example: Application component unavailable, unexpected exception.
     *
     * @param string $message
     * @param array $context
     * @return bool
     */
    public function critical($message, array $context = array())
    {
        return $this->log('critical', $message, $context);
    }

    /**
     * Runtime errors that do not require immediate action but should typically
     * be logged and monitored.
     *
     * @param string $message
     * @param array $context
     * @return bool
     */
    public function error($message, array $context = array())
    {
        return $this->log('error', $message, $context);
    }

    /**
     * Exceptional occurrences that are not errors.
     *
     * Example: Use of deprecated APIs, poor use of an API, undesirable things
     * that are not necessarily wrong.
     *
     * @param string $message
     * @param array $context
     * @return bool
     */
    public function warning($message, array $context = array())
    {
        return $this->log('warning', $message, $context);
    }

    /**
     * Normal but significant events.
     *
     * @param string $message
     * @param array $context
     * @return bool
     */
    public function notice($message, array $context = array())
    {
        return $this->log('notice', $message, $context);
    }

    /**
     * Interesting events.
     *
     * Example: User logs in, SQL logs.
     *
     * @param string $message
     * @param array $context
     * @return bool
     */
    public function info($message, array $context = array())
    {
        return $this->log('info', $message, $context);
    }

    /**
     * Detailed debug information.
     *
     * @param string $message
     * @param array $context
     * @return bool
     */
    public function debug($message, array $context = array())
    {
        return $this->log('debug', $message, $context);
    }

    /**
     * Get log file
     *
     * @return string
     * @throws \RuntimeException When unable to create logging directory
     */
    public function getFile()
    {
        if ($this->fileDetected) {
            return $this->file;
        }

        $this->handle = null;
        $file = &$this->file;

        if (!is_dir($this->dir) && false === @mkdir($this->dir, 0777, true)) {
            throw new \RuntimeException(sprintf('Unable to create directory "%s"', $this->dir));
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
     * @return Logger
     */
    public function setLevel($level)
    {
        $this->level = $level;
        return $this;
    }

    /**
     * Set handled level
     *
     * @param int $handledLevel The handled level
     * @return Logger
     */
    public function setHandledLevel($handledLevel)
    {
        $this->handledLevel = $handledLevel;
        return $this;
    }

    /**
     * Clear up all log file
     *
     * @return Logger
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

    /**
     * Close the file handle and reset to null
     */
    protected function close()
    {
        if (is_resource($this->handle)) {
            fclose($this->handle);
        }
        $this->handle = null;
    }

    /**
     * Destructor
     */
    public function __destruct()
    {
        $this->close();
    }
}
