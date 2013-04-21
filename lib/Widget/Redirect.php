<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget;

/**
 * The redirect response widget
 *
 * @author      Twin Huang <twinhuang@qq.com>
 * @method      Header header(string $name, string|array $values) Set the header value
 */
class Redirect extends Response
{
    /**
     * The custom view file
     * 
     * @var string
     */
    protected $view;
    
    /**
     * The seconds to wait before redirect
     * 
     * @var int
     */
    protected $delay = 0;

    /**
     * The default view content
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
     * @throws Exception\NotFoundException  When custom view file not found
     */
    public function __invoke($url = null, $status = 302, array $options = array())
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
    
    /**
     * Set redirect view file
     * 
     * @param string $view The view file
     * @return Redirect
     * @throws Exception\NotFoundException When view file not found
     */
    public function setView($view)
    {
        if (!is_file($view)) {
            throw new Exception\NotFoundException(sprintf('Redirect view "%s" not found', $view));
        }
        
        $this->view = $view;
        
        return $this;
    }
    
    /**
     * Set delay seconds
     * 
     * @param int $delay
     * @return Redirect
     */
    public function setDelay($delay)
    {
        $this->delay = (int)$delay;
        
        return $this;
    }
}
