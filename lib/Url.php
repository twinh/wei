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
        $url = $url . (false == strpos($url, '?') ? '?' : '&');
        return $this->request->getBaseUrl() . '/' . $url . (is_array($params) ? http_build_query($params) : $params);
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
}
