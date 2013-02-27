<?php

/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget;

/**
 * UrlDebugger
 *
 * @author      Twin Huang <twinh@yahoo.cn>
 * @property \Widget\Server $server The server widget
 * @method mixed config(string $name) Returns the configuration value
 * @property \Widget\Get $get The get widget
 * @todo        more options
 */
class UrlDebugger extends AbstractWidget
{
    /**
     * Constructor
     * 
     * @param array $options
     */
    public function __construct(array $options = array())
    {
        parent::__construct($options);

        if ($this->widget->config('debug')) {
            $this->inject();
        }
    }

    /**
     * Inject widget parameter by specified query parameter
     */
    public function inject()
    {
        if ($this->get['_ajax']) {
            $this->server['HTTP_X_REQUESTED_WITH'] = 'xmlhttprequest';
        }

        if ($this->get['_method']) {
            $this->server['REQUEST_METHOD'] = $this->get['_method'];
        }
    }

    /**
     * Invoker
     * 
     * @return \Widget\UrlDebugger
     */
    public function __invoke()
    {
        return $this;
    }
}
