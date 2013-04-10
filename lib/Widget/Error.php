<?php
/**
 * Widget Framework
 *
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget;

use Widget\Event\Event;

/**
 * The error widget to show pretty exception message 
 *
 * @property    \Widget\Request $request The HTTP request widget
 * @property    \Widget\Logger $logger The logger widget
 * @property    \Widget\Response $response The HTTP response widget
 * @method      \Widget\EventManager on(string|\Widget\Event $event) Attach a handler to an event
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
            $this, 'showError'
        ));

        if ($this->convertErrorToException) {
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
     * @param type $event
     * @param type $widget
     * @param type $exception
     */
    public function showError(Event $event, $widget, $exception)
    {
        $event->preventDefault();
        $debug = $this->widget->config('debug');
        try {
            $code       = $exception->getCode();
            $message    = htmlspecialchars($debug ? $exception->getMessage() : $this->message, ENT_QUOTES);
            $file       = $exception->getFile();
            $line       = $exception->getLine();
            $class      = get_class($exception);
            $trace      = htmlspecialchars($exception->getTraceAsString(), ENT_QUOTES);
            $detail     = sprintf('Threw by %s in %s on line %s', $class, $file, $line);
            
            $this->response->setStatusCode(500)->send();
            
            $this->logger->debug($detail);
            
            if ($this->request->inAjax()) {
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
        // Dispaly basic error message for exception in exception handler
        } catch (\Exception $e) {
            if ($debug) {
                echo sprintf('<p>%s: %s in %s on line %s</p>', get_class($e), $e->getMessage(), $e->getFile(), $e->getCode());
                echo '<pre>' . $e->getTraceAsString() . '</pre>';
            } else {
                echo $this->message;
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
                $content .= '<span class="error-text">' . htmlspecialchars($temp) . '</span>';
            }
        }
        
        return $content;
    }
}
