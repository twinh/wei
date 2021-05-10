<?php

/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2020 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

/**
 * A logger service, which is inspired by Monolog
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
    protected $namespace = '';

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
    protected $levels = [
        'debug' => 100,
        'info' => 200,
        'notice' => 250,
        'warning' => 300,
        'error' => 400,
        'critical' => 500,
        'alert' => 550,
        'emergency' => 600,
    ];

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
    protected $dateFormat = 'H:i:s';

    /**
     * The log file name, if specify this parameter, the "dir" and "fileFormat"
     * parameters would be ignored
     *
     * @var string|null
     */
    protected $file;

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
     * Whether log file's exact path has been detected, when set dir, fileFormat
     * or fileSize options, log file should be detected again
     *
     * @var bool
     */
    protected $fileDetected = false;

    /**
     * A key-value array that append to the log message
     *
     * @var array
     */
    protected $context = [];

    /**
     * The log file handle
     *
     * @var resource|null
     */
    private $handle;

    /**
     * Destructor
     */
    public function __destruct()
    {
        $this->close();
    }

    /**
     * Logs with an arbitrary level
     *
     * @param mixed $level
     * @param string $message
     * @param mixed $context
     * @return bool
     */
    public function __invoke($level, $message, $context = [])
    {
        return $this->log($level, $message, $context);
    }

    /**
     * Logs with an arbitrary level
     *
     * @param mixed $level
     * @param string $message
     * @param mixed $context
     * @return bool Whether the log record has been handled
     */
    public function log($level, $message, $context = [])
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
     * System is unusable.
     *
     * @param string $message
     * @param mixed $context
     * @return bool
     */
    public function emergency($message, $context = [])
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
     * @param mixed $context
     * @return bool
     */
    public function alert($message, $context = [])
    {
        return $this->log('alert', $message, $context);
    }

    /**
     * Critical conditions.
     *
     * Example: Application component unavailable, unexpected exception.
     *
     * @param string $message
     * @param mixed $context
     * @return bool
     */
    public function critical($message, $context = [])
    {
        return $this->log('critical', $message, $context);
    }

    /**
     * Runtime errors that do not require immediate action but should typically
     * be logged and monitored.
     *
     * @param string $message
     * @param mixed $context
     * @return bool
     */
    public function error($message, $context = [])
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
     * @param mixed $context
     * @return bool
     */
    public function warning($message, $context = [])
    {
        return $this->log('warning', $message, $context);
    }

    /**
     * Normal but significant events.
     *
     * @param string $message
     * @param mixed $context
     * @return bool
     */
    public function notice($message, $context = [])
    {
        return $this->log('notice', $message, $context);
    }

    /**
     * Interesting events.
     *
     * Example: User logs in, SQL logs.
     *
     * @param string $message
     * @param mixed $context
     * @return bool
     */
    public function info($message, $context = [])
    {
        return $this->log('info', $message, $context);
    }

    /**
     * Detailed debug information.
     *
     * @param string $message
     * @param mixed $context
     * @return bool
     */
    public function debug($message, $context = [])
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

        if (!is_dir($this->dir) && !@mkdir($this->dir, 0755, true)) {
            $message = sprintf('Fail to create directory "%s"', $this->dir);
            ($e = error_get_last()) && $message .= ': ' . $e['message'];
            throw new \RuntimeException($message);
        }

        $file = realpath($this->dir) . '/' . date($this->fileFormat);

        if ($this->fileSize) {
            $firstFile = $file;

            $files = glob($file . '*', \GLOB_NOSORT);

            if (1 < count($files)) {
                natsort($files);
                $file = array_pop($files);
            }

            if (is_file($file) && $this->fileSize < filesize($file)) {
                $ext = pathinfo($file, \PATHINFO_EXTENSION);
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
     * Add one or multi item for log message
     *
     * @param array|string $name
     * @param mixed $value
     * @return $this
     */
    public function setContext($name, $value = null)
    {
        if (is_array($name)) {
            $this->context = $name + $this->context;
        } else {
            $this->context[$name] = $value;
        }
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
                    $file = $dir . \DIRECTORY_SEPARATOR . $file;
                    if (is_file($file)) {
                        unlink($file);
                    }
                }
            }
        }
        return $this;
    }

    /**
     * Write the log message
     *
     * @param string $level
     * @param string $message
     * @param mixed $context
     * @return bool
     */
    protected function writeLog($level, $message, $context)
    {
        if (!$this->handle) {
            $this->handle = fopen($this->getFile(), 'a');
        }
        $content = $this->formatLog($level, $message, $context);
        return (bool) fwrite($this->handle, $content);
    }

    /**
     * Format the log content
     *
     * @param string $level
     * @param string $message
     * @param array $context
     * @return string
     */
    protected function formatLog($level, $message, $context = [])
    {
        // Format message and content
        $params = $this->formatParams($message, $context);
        $message = $params['message'];
        $context = $params['context'];

        // Format log content
        $content = str_replace([
            '%datetime%', '%namespace%', '%level%', '%message%',
        ], [
            date($this->dateFormat, microtime(true)),
            $this->namespace,
            strtoupper($level),
            $message,
        ], $this->format);

        // Format extra context
        if ($this->context || $context) {
            $content .= $this->formatContext($this->context + $context);
        }

        return $content;
    }

    /**
     * Convert message to string and content to array for writing
     *
     * @param string|\Exception $message
     * @param string|array $context
     * @return array
     */
    protected function formatParams($message, $context)
    {
        if (!is_array($context)) {
            $context = ['context' => $context];
        }
        if ($message instanceof \Exception) {
            $context += [
                'code' => $message->getCode(),
                'file' => $message->getFile(),
                'line' => $message->getLine(),
                'trace' => $message->getTraceAsString(),
            ];
            $message = $message->getMessage();
        } elseif (is_array($message)) {
            $message = print_r($message, true);
        } else {
            $message = (string) $message;
        }
        return ['message' => $message, 'context' => $context];
    }

    /**
     * Convert context to string
     *
     * @param array $context
     * @return string
     * @see
     */
    protected function formatContext(array $context): string
    {
        return str_replace(
            ['\r', '\n'],
            ["\r", "\n"],
            json_encode($context, \JSON_PRETTY_PRINT | \JSON_UNESCAPED_SLASHES | \JSON_UNESCAPED_UNICODE)
        ) . "\n";
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
}
