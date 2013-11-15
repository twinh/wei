<?php
/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

/**
 * A helper service to generate the URL
 *
 * @author      Twin Huang <twinhuang@qq.com>
 * @property    Request $request A service that handles the HTTP request data
 * @property    Router $router A service that parse the URL to request data
 */
class Url extends Base
{
    /**
     * Generate the URL by specified URL and parameters
     *
     * @param string $url
     * @param array $params
     * @return string
     */
    public function __invoke($url, $params = array())
    {
        return $this->router->generatePath($this->parse($url) + $params);
    }

    /**
     * Generate the full URL path by specified URL and parameters
     *
     * @param string $url
     * @param array $params
     * @return string
     */
    public function full($url, $params = array())
    {
        return $this->request->getUrlFor($this->__invoke($url, $params));
    }

    /**
     * Parse the URL by router
     *
     * @param string $url
     * @return array
     */
    protected function parse($url)
    {
        $url = $this->request->getBaseUrl() . '/' . $url;
        $params = $this->router->match($url);
        if (is_array($params)) {
            unset($params['_id']);
            return $params;
        } else {
            return array();
        }
    }
}
