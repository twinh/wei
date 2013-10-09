<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Widget;

/**
 * A service that allows inject data from HTTP request parameters
 *
 * @author      Twin Huang <twinhuang@qq.com>
 * @property    Request    $request A service that handles the HTTP request data
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
        $this->inject();
    }

    /**
     * Inject request parameters
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
