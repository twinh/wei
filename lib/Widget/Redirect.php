<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Widget;

/**
 * A widget that send a redirect response
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class Redirect extends Response
{
    /**
     * The custom redirect view file
     *
     * @var string
     */
    protected $view;

    /**
     * The seconds to wait before redirect
     *
     * @var int
     */
    protected $wait = 0;

    /**
     * The redirect status code
     *
     * @var int
     */
    protected $statusCode = 302;

    /**
     * The default view content
     *
     * @var string
     */
    protected $defaultHtml = '<!DOCTYPE html>
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
     * @param  array          $options The redirect widget options
     * @return Redirect
     */
    public function __invoke($url = null, $options = array())
    {
        $this->setOption($options);

        // The variables for custom redirect view
        $escapedUrl = htmlspecialchars($url, ENT_QUOTES, 'UTF-8');
        $wait = $this->wait;

        // Location header does not support delay
        if (0 === $wait) {
            $this->setHeader('Location', $url);
        }

        // Prepare response content
        if ($this->view) {
            ob_start();
            require $this->view;
            $content = ob_get_clean();
        } else {
            $content = sprintf($this->defaultHtml, $wait, $escapedUrl);
        }

        return $this->send($content, $this->statusCode);
    }

    /**
     * Set redirect view file
     *
     * @param string $view The view file
     * @return Redirect
     * @throws \RuntimeException When view file not found
     */
    public function setView($view)
    {
        if (!is_file($view)) {
            throw new \RuntimeException(sprintf('Redirect view file "%s" not found', $view));
        }
        $this->view = $view;
        return $this;
    }

    /**
     * Set wait seconds
     *
     * @param int $wait
     * @return Redirect
     */
    public function setWait($wait)
    {
        $this->wait = (int)$wait;
        return $this;
    }
}
