<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget;

/**
 * Flush
 *
 * @package     Widget
 * @author      Twin Huang <twinh@yahoo.cn>
 * @property \Widget\App $app The application widget
 */
class Flush extends AbstractWidget
{
    public function __construct(array $options = array())
    {
        parent::__construct($options);

        // how about other server
        apache_setenv('no-gzip', '1');

        ob_implicit_flush();

        // todo $buffer = ini_get('output_buffering');
        // echo str_repeat(' ',$buffer+1);
        // NOTE output here !!!
        echo str_pad('',4096);
    }

    /**
     * flush content to the browser
     *
     * @param  string     $content the content flushes to the browser
     * @param  int        $sleep   the second to sleep
     * @return Flush
     */
    public function __invoke($content, $sleep = 0)
    {
        $level = ob_get_level();
        if (0 == $level) {
            ob_start();
        } else {
            while (1 != ob_get_level()) {
                ob_end_flush();
            }
        }

        echo $content;

        ob_flush();

        $sleep && sleep($sleep);

        return $this;
    }
}
