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
 * @method mixed config(string $name) Get the widget config value
 * @property \Widget\Get $get The get widget
 * @property \Widget\Post $post The post widget
 * @property \Widget\Session $session The session widget
 * @property \Widget\Cookie $cookie The cookie widget
 * @property \Widget\Server $server The server widget
 * @property \Widget\Logger $logger The logger widget
 * @method \Widget\EventManager on(string|\Widget\Event $event) Attach a handler to an event
 * @method bool inAjax() Check if in ajax request
 * @todo        throw exception when called
 * @todo        add options display
 * @todo        response
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

        $this->on('exception', $this);

        if ($this->error) {
            set_error_handler(array($this, 'renderError'));
        }
    }

    /**
     * Show error message
     *
     * @param mixed $message
     * @param integer $code
     * @param array $options
     */
    public function __invoke($event, $widget, $message, $code = 500, array $options = array())
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
            if ($this->inAjax()) {
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

            // System Information
            $requestMethod = $this->server['REQUEST_METHOD'];

            $requestUrl = htmlspecialchars(urldecode($this->server['REQUEST_URI']), ENT_QUOTES);;

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

            // Response Information
            $response = $this->getResponse();

            // Server Environment
            $server = $this->getServer();

            $this->log->debug($code . $message . ' ' . $stackInfo);

            //$this->trigger('error', array('data' => get_defined_vars()));

            // display view file
            require __DIR__ . '/Resource/views/error.php';

            // exit to prevent other output
            if ($this->exit) {
                // @codeCoverageIgnoreStart
                exit();
                // @codeCoverageIgnoreEnd
            } else {
                if ($this->error) {
                    set_error_handler(array($this, 'renderError'));
                }
            }
        // @codeCoverageIgnoreStart
        // dispaly basic error message for exception in exception handler
        } catch (Exception $e) {
            if ($this->widget->config('debug')) {
                echo sprintf('<p>%s: %s in %s on line %s</p>', get_class($e), $e->getMessage(), $e->getFile(), $e->getCode());
                echo '<pre>' . $e->getTraceAsString() . '</pre>';
            } else {
                echo get_class($e) . ': ' . $e->getMessage();
            }
        }
        // @codeCoverageIgnoreEnd
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
     * 获取文件指定行号周围的代码
     *
     * @param  string $file  文件路径
     * @param  int    $line  文化行号
     * @param  int    $range 获取的总行数
     * @return string 文件代码
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

    /**
     * Get readable server($_SERVER) information for html output
     *
     * @return array
     */
    public function getServer()
    {
        $server = array();
        foreach ($this->server as $key => $value) {
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
            $server[$key] = htmlspecialchars((string) $value, ENT_QUOTES);
        }

        return $server;
    }

    /**
     * Get reqeust information ($_GET) for html output
     *
     * @return array
     */
    public function getGet()
    {
        $get = array();
        foreach ($this->get as $key => $value) {
            if (is_array($value)) {
                $value = var_export($value, true);
            }
            $get[$key] = htmlspecialchars($value, ENT_QUOTES);
        }

        return $get;
    }

    /**
     * Get reqeust information ($_POST) for html output
     *
     * @return array
     */
    public function getPost()
    {
        $post = array();
        foreach ($this->post as $key => $value) {
            if (is_array($value)) {
                $value = var_export($value, true);
            }
            $post[$key] = htmlspecialchars($value, ENT_QUOTES);
        }

        return $post;
    }

    /**
     * Get reqeust information ($_COOKIE) for html output
     *
     * @return array
     */
    public function getCookie()
    {
        $cookie = array();
        foreach ($this->cookie as $key => $value) {
            $cookie[$key] = htmlspecialchars($value, ENT_QUOTES);
        }

        return $cookie;
    }

    /**
     * Get session information ($_SESSION) for html output
     *
     * @return string|array
     * @todo when session not enable ?
     */
    public function getSession()
    {
        // Session Information
        $session = array();

        if (!@$this->session) {
            return 'Unable to get sesion object, may be some errors occurred when called Widget_Session::__construct';
        }

        foreach ($this->session as $key => $value) {
            if (is_array($value)) {
                $value = var_export($value, true);
            }
            $session[$key] = htmlspecialchars($value, ENT_QUOTES);
        }

        return $session;
    }

    /**
     * Get response information for html output
     *
     * @return array
     */
    public function getResponse()
    {
        if (function_exists('apache_response_headers')) {
            $headers = apache_response_headers();
        } else {
            $headers = array();

            foreach (headers_list() as $header) {
                $pos = strpos($header, ':');
                $headers[substr($header, 0, $pos)] = trim(substr($header, $pos + 1));
            }
        }

        foreach ($headers as &$header) {
            $header = htmlspecialchars($header, ENT_QUOTES);
        }

        return $headers;
    }
}
