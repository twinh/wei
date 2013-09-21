<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Widget;

/**
 * The widget allows inject data from URL query string
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class UrlDebugger extends Base
{
    /**
     * Constructor
     *
     * @param array $options
     */
    public function __construct(array $options = array())
    {
        parent::__construct($options);

        if ($this->widget->isDebug()) {
            $this->inject();
        }
    }

    /**
     * Inject widget parameter by specified query parameter
     */
    public function inject()
    {
        if ($this->request->get('_ajax')) {
            $this->request->setServer('HTTP_X_REQUESTED_WITH', 'xmlhttprequest');
        }

        if ($method = $this->request->get('_method')) {
            $this->request->setServer('REQUEST_METHOD', $method);
        }
    }
}
