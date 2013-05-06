<?php
/**
 * Widget Framework
 *
 * @copyright   Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Widget;

use Widget\Event\Event;

/**
 * The error widget to show pretty exception message 
 *
 * @property    Request $request The HTTP request widget
 * @property    Logger $logger The logger widget
 * @property    Response $response The HTTP response widget
 * @method      EventManager on(string|Event $event) Attach a handler to an event
 */
class Error extends AbstractWidget
{
    /**
     * The default error message when debug is not enable
     *
     * @var string
     */
    protected $message = 'Error';

    /**
     * Whether handle the PHP errors
     *
     * @var bool
     */
    protected $convertErrorToException = true;
    
    /**
     * Constructor
     *
     * @param array $options
     */
    public function __construct(array $options = array())
    {
        parent::__construct($options);

        $this->on('exception', array(
            $this, 'handleException'
        ));

        if ($this->convertErrorToException) {
            set_error_handler(array($this, 'hanldeError'));
        }
    }
    
    /**
     * Attach a handler to the error event
     * 
     * @param \Closure $fn The error handler
     * @param int|string $priority The event priority, could be int or specify strings, the higer number, the higer priority
     * @param array $data The data pass to the event object, when the handler is triggered
     * @return EventManager
     */
    public function __invoke(\Closure $fn, $priority = 1, $data = array())
    {
        return $this->on('exception', $fn, $priority, $data);
    }

    /**
     * The exception handler to render pretty message
     * 
     * @param Event\Event $event
     * @param Widget $widget
     * @param \Exception $exception
     */
    public function handleException(Event $event, $widget, $exception)
    {
        // Prevent ogirin exception output
        $event->preventDefault();
        
        $debug = $widget->config('debug');
        $ajax = $this->request->inAjax();
        
        try {
            // This widgets may show exception too
            $this->response->setStatusCode(500)->send();
            $this->logger->debug((string)$exception);
            
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
