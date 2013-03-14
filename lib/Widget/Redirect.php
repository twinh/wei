<?php

/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget;

use Widget\Exception;

/**
 * Redirect
 *
 * @author      Twin Huang <twinh@yahoo.cn>
 * @method \Widget\Header header(string $name, string|array $values) Set the header value
 */
class Redirect extends Response
{
    /**
     * The custom view file
     * 
     * @var string
     */
    protected $view;
    
    protected $delay = 0;

    /**
     * Default view content
     *
     * @var string
     */
    protected static $html = '<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="refresh" content="%d;url=%2$s">
    <title>Redirect to %s</title>
  </head>
  <body>
    <h1>Redirecting to <a href="%2$s">%2$s</a></h1>
  </body>
</html>';

    /**
     * Send a redirect response
     *
     * @param  string         $url     The url redirect to
     * @param  int            $status  The redirect status code
     * @param  array          $options The widget options
     * @return Redirect
     * @throws Exception      When custom view file not found
     */
    public function __invoke($url = '', $status = 302, array $options = array())
    {
        $options = $this->setOption($options);
        
        if ($this->view) {
            require $this->view;
        } else {
            // Location header does not support delay
            if (0 === $this->delay) {
                $this->header('Location', $url);
            }
            
            $content = sprintf(static::$html, $this->delay, htmlspecialchars($url, ENT_QUOTES, 'UTF-8'));

            parent::__invoke($content, $status);
        }

        return $this;
    }
    
    public function setView($view)
    {
        if (!is_file($view)) {
            throw new Exception\NotFoundException(sprintf('File "%s" not found'));
        }
        
        $this->view = $view;
        
        return $this;
    }
    
    public function setDelay($delay)
    {
        $this->delay = (int)$delay;
        
        return $this;
    }
}
