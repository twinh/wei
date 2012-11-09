<?php

/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2012 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget;

/**
 * Redirect
 *
 * @package     Widget
 * @author      Twin Huang <twinh@yahoo.cn>
 * @todo        custome view
 */
class Redirect extends Response
{
    /**
     * Options
     *
     * @var array
     */
    public $options = array(
        'view' => false,
        'delay' => 0,
    );

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
     * send a redirect response
     *
     * @param  string         $url     The url redirect to
     * @param  int            $status  The redirect status code
     * @param  array          $options The widget options
     * @return \Widget\Redirect
     * @throws Exception      When custom view file not found
     */
    public function __invoke($url = '', $status = 302, array $options = array())
    {
        $options = $this->option($options);

        // use custom view file for redirect
        if ($options['view']) {
            if (is_file($options['view'])) {
                $this->setSentStatus(true);
                require $options['view'];
            } else {
                throw new Exception(sprintf('View file "%s" not found', $options['view']));
            }
        } else {
            $options['delay'] = (int) $options['delay'];

            // Location header does not support delay
            if ($options['delay'] === 0) {
                $this->header('Location', $url);
            }

            $content = sprintf(static::$html, $options['delay'], htmlspecialchars($url, ENT_QUOTES, 'UTF-8'));

            parent::__invoke($content, $status);
        }

        return $this;
    }
}
