<?php
/**
 * Widget Framework
 *
 * @copyright   Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Widget;

/**
 * The error widget to show pretty exception message
 *
 * @property    Request $request The HTTP request widget
 * @property    Logger $logger The logger widget
 * @property    Response $response The HTTP response widget
 * @todo        HTTP status code support
 */
class Error extends AbstractWidget
{
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
     * Whether ignore the previous exception handler or attch it again to the
     * exception event
     *
     * @var bool
     */
    protected $ignorePrevHandler = false;

    /**
     * The previouse exception handler
     *
     * @var null|callback
     */
    protected $prevExceptionHandler;

    /**
     * The custom error handlers
     *
     * @var array
     */
    protected $handlers = array(
        'error'     => array(),
        'fatal'     => array(),
        'notFound'  => array()
    );

    /**
     * The 404 not found exception classes
     *
     * @var array
     */
    protected $notFoundExceptions = array(
        'Widget\Exception\NotFoundException'
    );

    /**
     * Constructor
     *
     * @param array $options
     */
    public function __construct($options = array())
    {
        parent::__construct($options);

        $this->registerErrorHandler();
        $this->registerExceptionHandler();
        $this->registerFatalHandler();
    }

    /**
     * Attach a handler to exception error
     *
     * @param callback $fn The error handler
     * @return Error
     */
    public function __invoke($fn)
    {
        $this->handlers['error'][] = $fn;

        return $this;
    }

    /**
     * Attach a handler to not found error
     *
     * @param callback $fn The error handler
     * @return Error
     */
    public function notFound($fn)
    {
        $this->handlers['notFound'][] = $fn;

        return $this;
    }

    /**
     * Attach a handler to fatal error
     *
     * @param callback $fn The error handler
     * @return Error
     */
    public function fatal($fn)
    {
        $this->handlers['fatal'][] = $fn;

        return $this;
    }

    /**
     * Register exception hanlder
     */
    protected function registerExceptionHandler()
    {
        $this->prevExceptionHandler = set_exception_handler(array($this, 'handleException'));
    }

    /**
     * Register error hanlder
     */
    protected function registerErrorHandler()
    {
        set_error_handler(array($this, 'hanldeError'));
    }

    /**
     * Detecte fatal error and register fatal handler
     */
    protected function registerFatalHandler()
    {
        $error = $this;

        // When shutdown, the current working directory will be set to the web
        // server directory, store it for later use
        $cwd = getcwd();

        register_shutdown_function(function() use($error, $cwd) {
            $e = error_get_last();
            if (!$e || !in_array($e['type'], array(E_ERROR, E_CORE_ERROR, E_COMPILE_ERROR, E_PARSE))) {
                // No error or not fatal error
                return;
            }

            ob_end_clean();

            // Reset the current working directory to make sure everything work as usual
            chdir($cwd);

            $exception = new \ErrorException($e['message'], $e['type'], 0, $e['file'], $e['line']);

            if ($error->triggerHandler('fatal', $exception)) {
                // Handled!
                return;
            }

            // Fallback to error handlers
            if ($error->triggerHandler('error', $exception)) {
                // Handled!
                return;
            }

            // Fallback to internal error hanlders
            $error->internalHanldeException($exception);
        });
    }

    /**
     * Trigger a error handler
     *
     * @param string $type The type of error handlers
     * @param \Exception $exception
     * @return bool
     * @internal description
     */
    public function triggerHandler($type, \Exception $exception)
    {
        foreach ($this->handlers[$type] as $handler) {
            $result = call_user_func_array($handler, array($exception, $this->widget));
            if (true === $result) {
                return true;
            }
        }
        return false;
    }

    /**
     * The exception handler to render pretty message
     *
     * @param Event\Event $event
     * @param Widget $widget
     * @param \Exception $exception
     */
    public function handleException(\Exception $exception)
    {
        if (!$this->ignorePrevHandler && $this->prevExceptionHandler) {
            call_user_func($this->prevExceptionHandler, $exception);
        }

        if ($this->handlers['notFound']) {
            foreach ($this->notFoundExceptions as $class) {
                if ($exception instanceof $class) {
                    if ($this->triggerHandler('notFound', $exception)) {
                        return;
                    }
                }
            }
        }

        if (!$this->triggerHandler('error', $exception)) {
            $this->internalHanldeException($exception);
        }

        restore_exception_handler();
    }

    public function internalHanldeException($exception)
    {
        $code = 500;
        $debug = $this->widget->config('debug');
        $ajax = $this->request->inAjax();

        try {
            // This widgets may show exception too
            $this->response->setStatusCode($code)->send();
            $this->logger->critical((string)$exception);

            $this->renderException($exception, $debug, $ajax);
        } catch (\Exception $e) {
            $this->renderException($e, $debug, $ajax);
        }
    }

    /**
     * Render exception message
     *
     * @param \Exception $exception
     * @param bool $debug Whether show debug trace
     * @param bool $ajax Wherher return json instead html string
     */
    public function renderException(\Exception $exception, $debug, $ajax)
    {
        $code       = $exception->getCode();
        $message    = htmlspecialchars($debug ? $exception->getMessage() : $this->message, ENT_QUOTES);
        $file       = $exception->getFile();
        $line       = $exception->getLine();
        $class      = get_class($exception);
        $trace      = htmlspecialchars($exception->getTraceAsString(), ENT_QUOTES);
        $detail     = sprintf('Threw by %s in %s on line %s', $class, $file, $line);

        if ($ajax) {
            echo json_encode(array(
                'code'      => -($code ? abs($code) : 500),
                'message'   => $message,
                'detail'    => $detail,
                'trace'     => $trace
            ));
        } else {
            // File Infomation
            $mtime = date('Y-m-d H:i:s', filemtime($file));
            $fileInfo = $this->getFileCode($file, $line);
            !$debug && $detail = $this->detail;

            // Display view file
            require __DIR__ . '/Resource/views/error.php';
        }
    }

    /**
     * The error handler convert PHP error to exception
     *
     * @param int    $errno     The level of the error raised
     * @param string $errstr    The error message
     * @param string $errfile   The filename that the error was raised in
     * @param int    $errline   The line number the error was raised at
     * @internal use for set_error_handler only
     */
    public function hanldeError($errno, $errstr, $errfile, $errline)
    {
        if (!(error_reporting() & $errno)) {
            // This error code is not included in error_reporting
            return;
        }
        restore_error_handler();
        throw new \ErrorException($errstr, $errno, 500, $errfile, $errline);
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

        $start = $line - $half;
        0 > $start && $start = 0;

        $total = count($code);
        $end = $line + $half;
        $total < $end && $end = $total;

        $len = strlen($end);

        array_unshift($code, null);
        $content = '';
        for ($i = $start; $i < $end; $i++) {
            $temp = str_pad($i, $len, 0, STR_PAD_LEFT) . ':  ' . $code[$i];
            if ($line != $i) {
                $content .= htmlspecialchars($temp, ENT_QUOTES);
            } else {
                $content .= '<strong>' . htmlspecialchars($temp, ENT_QUOTES) . '</strong>';
            }
        }

        return $content;
    }
}
