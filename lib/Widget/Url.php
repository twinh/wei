<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Widget;

/**
 * A helper service to generate the URL
 *
 * @author      Twin Huang <twinhuang@qq.com>
 * @property    Request $request A service that handles the HTTP request data
 */
class Url extends Base
{
    /**
     * Generate the URL and automatic prepend the base URL
     *
     * @param string $url
     * @param array $params
     * @return string
     */
    public function __invoke($url = null, $params = array())
    {
        if (is_array($params)) {
            $url .= http_build_query($params);
        }
        return $this->request->getBaseUrl() . $url;
    }

    /**
     * Build full URL by specified uri and parameters
     *
     * @return string
     */
    public function full()
    {
        $arr = call_user_func_array(array($this, 'parse'), func_get_args());

        return $this->router->generateUrl($arr);
    }
}
