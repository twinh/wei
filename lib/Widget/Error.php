<?php
/**
 * Widget Framework
 *
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget;

/**
 * Error
 *
 * @property    \Widget\Request $request The HTTP request widget
 * @property    \Widget\Logger $logger The logger widget
 * @property    \Widget\Response $response The HTTP response widget
 * @method      \Widget\EventManager on(string|\Widget\Event $event) Attach a handler to an event
 * @todo        throw exception when called
 * @todo        add options display
 * @todo        bootstrap style
 */
class Error extends AbstractWidget
{
    /**
     * The default error code
     *
     * @var int
     */
    protected $code = 500;

    /**
     * The default error message when debug is not enable
     *
     * @var string
     */
    protected $message = 'Server busy, please try again later';

    /**
     * Whether exit after dispaly the error message or not
     *
     * @var bool
     */
    protected $exit = true;

    /**
     * Whether clean previous output before error or not
     *
     * @var bool
     */
    protected $clean = true;

    /**
     * Whether handle the php error
     *
     * @var bool
     */
    protected $error = true;

    /**
     * Constructor
     *
     * @param array $options
     */
    public function __construct(array $options = array())
    {
        parent::__construct($options);

        $this->on('exception', array(
            $this, 'showError'
        ));

        if ($this->error) {
            set_error_handler(array($this, 'renderError'));
        }
    }
    
    /**
     * Attach a handler to the error event
     * 
     * @param \Closure $fn The error handler
     * @return \Widget\EventManager
     */
    public function __invoke(\Closure $fn)
    {
        return $this->on('error', $fn);
    }

    /**
     * Show error message
     *
     * @param mixed $message
     * @param integer $code
     * @param array $options
     */
    public function showError($event, $widget, $message, $code = 500, array $options = array())
    {
        try {
            if ($this->error) {
                restore_error_handler();
            }

            if ($message instanceof \Exception) {
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
                    $message = isset($options['message']) ? $options['message'] : $this->message;
                } else {
                    $message = (string) $message;
                }

                $offset = 5;
                $traces = debug_backtrace();
                $file = $traces[$offset-1]['file'];
                $line = $traces[$offset-1]['line'];
                $class = 'Widget_Error';
                $traces = array_slice($traces, $offset);
                $trace = $this->getTraceString($traces);
            }

            $this->setOption($options);

            $debug = $this->widget->config('debug');

            // clean up output
            if ($this->clean && ob_get_status()) {
                $output = ob_get_contents();
                $output && ob_end_clean();
                ob_start();
            }

            // TODO more info, may be need an ajax view file
            if ($this->request->inAjax()) {
                exit(json_encode(array(
                    'code' => $code ? ($code > 0 ? -$code : $code) : -$this->code,
                    'message' => $message,
                    'detail' => sprintf('%s: %s in %s on line %s', get_class($e), $e->getMessage(), $e->getFile(), $e->getCode()),
                    'trace' => $e->getTraceAsString(),
                )));
            }

            // Title & Message
            $code = $code ? $code . ': ' : '';

            // Call Stack
            $stackInfo = sprintf('Threw by %s in %s on line %s', $class, $file, $line);
            $trace = htmlspecialchars($trace, ENT_QUOTES);

            // File Infomation
            $mtime = strftime('%c', filemtime($file));
            $fileInfo = $this->getFileCode($file, $line);
            
            $this->logger->debug($code . $message . ' ' . $stackInfo);

            //$this->trigger('error', array('data' => get_defined_vars()));

            // display view file
            $this->response->setStatusCode(500)->send();
            require __DIR__ . '/Resource/views/error.php';

            // exit to prevent other output
            if ($this->exit) {
                exit();
            } else {
                if ($this->error) {
                    set_error_handler(array($this, 'renderError'));
                }
            }
        // dispaly basic error message for exception in exception handler
        } catch (Exception $e) {
            if ($this->widget->config('debug')) {
                echo sprintf('<p>%s: %s in %s on line %s</p>', get_class($e), $e->getMessage(), $e->getFile(), $e->getCode());
                echo '<pre>' . $e->getTraceAsString() . '</pre>';
            } else {
                echo get_class($e) . ': ' . $e->getMessage();
            }
        }
    }

    /**
     * Error handler
     *
     * @param int    $errno
     * @param string $errstr
     * @param string $errfile
     * @param int    $errline
     */
    public function renderError($errno, $errstr, $errfile, $errline)
    {
        if (!(error_reporting() & $errno)) {
            return;
        }
        restore_error_handler();
        throw new \ErrorException($errstr, 500, $errno, $errfile, $errline);
    }

    /**
     * Get file code in specified range
     *
     * @param  string $file  The file name
     * @param  int    $line  The file line 
     * @param  int    $range The line range
     * @return string
     */
    public function getFileCode($file, $line, $range = 20)
    {
        $code = file($file);

        $half = (int) ($range / 2);

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
        for ($i = $start; $i < $end; $i++) {
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
     * @param  array  $traces usally get from debug_backtrace()
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
                    $args[] = (string) $arg;
                }
            }
            $str .= '(' . implode(', ', $args) . ')' . PHP_EOL;
        }
        $str .= '#' . ++$i . ' {main}' . PHP_EOL . PHP_EOL;

        return $str;
    }
}
