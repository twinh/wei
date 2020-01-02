<?php
/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2020 Twin Huang
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
     * @param string|array $argsOrParams
     * @param string|array $params
     * @return string
     */
    public function __invoke($url = '', $argsOrParams = array(), $params = array())
    {
        return $this->request->getBaseUrl() . '/' . $this->append($url, $argsOrParams, $params);
    }

    /**
     * Generate the absolute URL path by specified URL and parameters
     *
     * @param string $url
     * @param string|array $argsOrParams
     * @param string|array $params
     * @return string
     */
    public function full($url, $argsOrParams = array(), $params = array())
    {
        return $this->request->getUrlFor($this->__invoke($url, $argsOrParams, $params));
    }

    /**
     * Generate the URL path with current query parameters and specified parameters
     *
     * @param string $url
     * @param string|array $argsOrParams
     * @param string|array $params
     * @return string
     */
    public function query($url = '', $argsOrParams = array(), $params = array())
    {
        if (strpos($url, '%s') === false) {
            $argsOrParams = $argsOrParams + $this->request->getQueries();
        } else {
            $params += $this->request->getQueries();
        }
        return $this->__invoke($url, $argsOrParams, $params);
    }

    /**
     * Append parameters to specified URL
     *
     * @param string $url
     * @param string|array $argsOrParams The arguments to replace in URL or the parameters append to the URL
     * @param string|array $params The parameters append to the URL
     * @return string
     */
    public function append($url = '', $argsOrParams = array(), $params = array())
    {
        if (strpos($url, '%s') !== false) {
            $url = vsprintf($url, (array)$argsOrParams);
        } else {
            $params = $argsOrParams;
        }
        if ($params) {
            $url .= (false === strpos($url, '?') ? '?' : '&');
        }
        return $url . (is_array($params) ? http_build_query($params) : $params);
    }
}
