<?php
/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2020 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

/**
 * A service that handles exception and display pretty exception message
 *
 * @mixin \LoggerMixin
 * @mixin \ResponseMixin
 * @mixin \RequestMixin
 */
class Error extends Base
{
    /**
     * The default error view
     *
     * @var string
     */
    protected $view;

    /**
     * The default error message display when debug is not enable
     *
     * @var string
     */
    protected $message = 'Error';

    /**
     * The detail error message display when debug is not enable
     *
     * @var string
     */
    protected $detail = 'Unfortunately, an error occurred. Please try again later.';

    /**
     * @var string
     */
    protected $view404;

    /**
     * @var string
     */
    protected $message404 = 'Page not found';

    /**
     * The detail error message display when thrown 404 exception
     *
     * @var string
     */
    protected $detail404 = 'Sorry, the page you requested was not found. Please check the URL and try again.';

    /**
     * Whether ignore the previous exception handler or attach it again to the
     * exception event
     *
     * @var bool
     */
    protected $ignorePrevHandler = false;

    /**
     * The previous exception handler
     *
     * @var callback|null
     */
    protected $prevExceptionHandler;

    /**
     * Whether render text error when PHP run in CLI mode
     *
     * @var bool
     */
    protected $enableCli = true;

    /**
     * Whether call exit after displayed CLI message
     *
     * @var bool
     */
    protected $autoExit = true;

    /**
     * The custom error handlers
     *
     * @var array
     */
    protected $handlers = [
        'error' => [],
        'fatal' => [],
        'notFound' => [],
    ];

    /**
     * Constructor
     *
     * @param array $options
     */
    public function __construct($options = [])
    {
        parent::__construct($options);

        $this->registerErrorHandler();
        $this->registerExceptionHandler();
        $this->registerFatalHandler();
    }

    /**
     * Attach a handler to exception error
     *
     * @param callable $fn The error handler
     * @return $this
     */
    public function __invoke($fn)
    {
        $this->handlers['error'][] = $fn;
        return $this;
    }

    /**
     * Attach a handler to not found error
     *
     * @param callable $fn The error handler
     * @return $this
     */
    public function notFound($fn)
    {
        $this->handlers['notFound'][] = $fn;
        return $this;
    }

    /**
     * Attach a handler to fatal error
     *
     * @param callable $fn The error handler
     * @return $this
     */
    public function fatal($fn)
    {
        $this->handlers['fatal'][] = $fn;
        return $this;
    }

    /**
     * Trigger a error handler
     *
     * @param string $type The type of error handlers
     * @param \Exception|\Throwable $exception
     * @return bool
     */
    public function triggerHandler($type, $exception)
    {
        foreach ($this->handlers[$type] as $handler) {
            $result = call_user_func_array($handler, [$exception, $this->wei]);
            if (true === $result) {
                return true;
            }
        }
        return false;
    }

    /**
     * The exception handler to render pretty message
     *
     * @param \Exception|\Throwable $exception
     */
    public function handleException($exception)
    {
        if (!$this->ignorePrevHandler && $this->prevExceptionHandler) {
            call_user_func($this->prevExceptionHandler, $exception);
        }

        if (404 == $exception->getCode()) {
            if ($this->triggerHandler('notFound', $exception)) {
                return;
            }
        }

        if (!$this->triggerHandler('error', $exception)) {
            $this->internalHandleException($exception);
        }

        restore_exception_handler();
    }

    /**
     * @param \Exception|\Throwable $e
     */
    public function internalHandleException($e)
    {
        $code = $e->getCode();
        $debug = $this->wei->isDebug();

        // HTTP status code
        if ($code < 100 || $code > 600) {
            $code = 500;
        }

        // Logger level
        if ($code >= 500) {
            $level = 'critical';
        } else {
            $level = 'info';
        }

        try {
            // The flowing services may throw exception too
            $this->response->setStatusCode($code)->send();
            $this->logger->log($level, $e);

            $this->displayException($e, $debug);
        } catch (\Exception $e) {
            $this->displayException($e, $debug);
        }
    }

    /**
     * Render exception message
     *
     * @param \Exception|\Throwable $e
     * @param bool $debug Whether show debug trace
     */
    public function displayException($e, $debug)
    {
        // Render CLI message
        if ($this->enableCli && \PHP_SAPI == 'cli') {
            $this->displayCliException($e);
            return;
        }

        // Render JSON message
        if ($this->request->acceptJson()) {
            $this->displayJsonException($e, $debug);
            return;
        }

        // Render HTML message
        $code = $e->getCode();
        $file = $e->getFile();
        $line = $e->getLine();

        $title = htmlspecialchars($this->getDisplayMessage($e, $debug), ENT_QUOTES);
        $message = nl2br($title);

        if (!$debug) {
            $view = isset($this->{'view' . $code}) ? $this->{'view' . $code} : $this->view;
            $detail = isset($this->{'detail' . $code}) ? $this->{'detail' . $code} : $this->detail;
            if ($view) {
                require $view;
                return;
            }
        } else {
            $detail = sprintf('Threw by %s in %s on line %s', get_class($e), $file, $line);

            $fileInfo = $this->getFileCode($file, $line);
            $trace = htmlspecialchars($e->getTraceAsString(), ENT_QUOTES);
            $detail = '<h2>File</h2>'
                . "<p class=\"text-danger\">$file</p>"
                . "<p><pre>$fileInfo</pre></p>"
                . '<h2>Trace</h2>'
                . "<p class=\"text-danger\">$detail</p>"
                . "<p><pre>$trace</pre></p>";
        }

        $html = '<!DOCTYPE html>'
            . '<html>'
            . '<head>'
            . '<meta name="viewport" content="width=device-width">'
            . '<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>'
            . "<title>$title</title>"
            . '<style type="text/css">'
            . 'body { font-size: 14px; color: #333; padding: 15px 20px 20px 20px; }'
            . 'h1, h2, p, pre { margin: 0; padding: 0; }'
            . 'body, pre '
            . '{ font-family: "Helvetica Neue", Helvetica, Arial, sans-serif, "\5fae\8f6f\96c5\9ed1", "\5b8b\4f53"; }'
            . 'h1 { font-size: 36px; }'
            . 'h2 { font-size: 20px; margin: 20px 0 0; }'
            . 'pre { font-size:13px; line-height: 1.42857143; }'
            . '.text-danger { color: #fa5b50 }'
            . '</style>'
            . '</head>'
            . '<body>'
            . "<h1>$message</h1>"
            . $detail
            . '</body>'
            . '</html>';

        echo $html;
    }

    /**
     * The error handler convert PHP error to exception
     *
     * @param int $code The level of the error raised
     * @param string $message The error message
     * @param string $file The filename that the error was raised in
     * @param int $line The line number the error was raised at
     * @throws \ErrorException convert PHP error to exception
     * @internal use for set_error_handler only
     */
    public function handleError($code, $message, $file, $line)
    {
        if (!(error_reporting() & $code)) {
            // This error code is not included in error_reporting
            return;
        }
        restore_error_handler();
        throw new \ErrorException($message, $code, 500, $file, $line);
    }

    /**
     * Get file code in specified range
     *
     * @param string $file The file name
     * @param int $line The file line
     * @param int $range The line range
     * @return string
     */
    public function getFileCode($file, $line, $range = 20)
    {
        $code = file($file);
        $half = (int) ($range / 2);

        $start = $line - $half;
        0 > $start && $start = 0;

        $total = count($code);
        $end = $line + $half;
        $total < $end && $end = $total;

        $len = strlen($end);

        array_unshift($code, null);
        $content = '';
        for ($i = $start; $i < $end; ++$i) {
            $temp = str_pad($i, $len, 0, STR_PAD_LEFT) . ':  ' . $code[$i];
            if ($line != $i) {
                $content .= htmlspecialchars($temp, ENT_QUOTES);
            } else {
                $content .= '<strong class="text-danger">' . htmlspecialchars($temp, ENT_QUOTES) . '</strong>';
            }
        }

        return $content;
    }

    /**
     * Register exception Handler
     */
    protected function registerExceptionHandler()
    {
        $this->prevExceptionHandler = set_exception_handler([$this, 'handleException']);
    }

    /**
     * Register error Handler
     */
    protected function registerErrorHandler()
    {
        set_error_handler([$this, 'handleError']);
    }

    /**
     * Detect fatal error and register fatal handler
     */
    protected function registerFatalHandler()
    {
        // When shutdown, the current working directory will be set to the web
        // server directory, store it for later use
        $cwd = getcwd();

        register_shutdown_function(function () use ($cwd) {
            $e = error_get_last();
            if (!$e || !in_array($e['type'], [E_ERROR, E_CORE_ERROR, E_COMPILE_ERROR, E_PARSE], true)) {
                // No error or not fatal error
                return;
            }

            ob_get_length() && ob_end_clean();

            // Reset the current working directory to make sure everything work as usual
            chdir($cwd);

            $exception = new \ErrorException($e['message'], $e['type'], 0, $e['file'], $e['line']);

            if ($this->triggerHandler('fatal', $exception)) {
                // Handled!
                return;
            }

            // Fallback to error handlers
            if ($this->triggerHandler('error', $exception)) {
                // Handled!
                return;
            }

            // Fallback to internal error Handlers
            $this->internalHandleException($exception);
        });
    }

    /**
     * Render exception message for CLI
     *
     * @param \Exception|\Throwable $e
     * @SuppressWarnings(PHPMD.ExitExpression)
     */
    protected function displayCliException($e)
    {
        echo 'Exception', PHP_EOL,
        $this->highlight($e->getMessage()),
        'File', PHP_EOL,
        $this->highlight($e->getFile() . ' on line ' . $e->getLine()),
        'Trace', PHP_EOL,
        $this->highlight($e->getTraceAsString());

        if ($this->autoExit) {
            exit($this->getExitCode($e));
        }
    }

    protected function displayJsonException(\Exception $e, $debug)
    {
        $data = [
            'code' => $e->getCode(),
            'message' => $this->getDisplayMessage($e, $debug),
        ];
        if ($debug) {
            $data += [
                'exception' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'traces' => $e->getTrace(),
            ];
        }
        echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }

    protected function getDisplayMessage($e, $debug)
    {
        if ($debug) {
            return $e->getMessage();
        }

        if (isset($this->{'message' . $e->getCode()})) {
            return $this->{'message' . $e->getCode()};
        }

        if ($message = $this->response->getStatusTextByCode($e->getCode())) {
            return $message;
        }

        return $this->message;
    }

    /**
     * @param \Exception|\Throwable $e
     * @return int
     */
    protected function getExitCode($e)
    {
        $code = $e->getCode();
        if (!$code || !is_numeric($code)) {
            return 1;
        }

        $code = (int) $code;
        if ($code > 255) {
            return 255;
        }

        return $code;
    }

    /**
     * Highlight text for CLI output
     *
     * @param string $text
     * @return string
     */
    protected function highlight($text)
    {
        return sprintf("\033[1;31m%s\033[0m" . PHP_EOL . PHP_EOL, $text);
    }
}
