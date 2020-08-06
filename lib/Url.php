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
 * @property    Req $req A service that handles the HTTP request data
 */
class Url extends Base
{
    /**
     * Invoke the "to" method
     *
     * @param string $url
     * @param string|array $argsOrParams
     * @param string|array $params
     * @return string
     */
    public function __invoke($url = '', $argsOrParams = [], $params = [])
    {
        return $this->to(...func_get_args());
    }

    /**
     * Generate the absolute URL path by specified URL and parameters
     *
     * @param string $url
     * @param string|array $argsOrParams
     * @param string|array $params
     * @return string
     */
    public function full($url, $argsOrParams = [], $params = [])
    {
        return $this->req->getUrlFor($this->__invoke($url, $argsOrParams, $params));
    }

    /**
     * Generate the URL path with current query parameters and specified parameters
     *
     * @param string $url
     * @param string|array $argsOrParams
     * @param string|array $params
     * @return string
     */
    public function query($url = '', $argsOrParams = [], $params = [])
    {
        if (false === strpos($url, '%s')) {
            $argsOrParams = $argsOrParams + $this->req->getQueries();
        } else {
            $params += $this->req->getQueries();
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
    public function append($url = '', $argsOrParams = [], $params = [])
    {
        if (false !== strpos($url, '%s')) {
            $url = vsprintf($url, (array) $argsOrParams);
        } else {
            $params = $argsOrParams;
        }
        if ($params) {
            $url .= (false === strpos($url, '?') ? '?' : '&');
        }
        return $url . (is_array($params) ? http_build_query($params) : $params);
    }

    /**
     * Generate the URL by specified URL and parameters
     *
     * @param string $url
     * @param array $argsOrParams
     * @param array $params
     * @return string
     * @svc
     */
    protected function to($url = '', $argsOrParams = [], $params = [])
    {
        if ($this->req->isUrlRewrite()) {
            return $this->req->getBaseUrl() . '/' . $this->append($url, $argsOrParams, $params);
        }

        if (false !== strpos($url, '%s')) {
            $url = $this->append($url, $argsOrParams);
            $argsOrParams = $params;
        }

        // Add router path info into url
        if ($url) {
            $argsOrParams = [$this->req->getRouterKey() => $url] + $argsOrParams;
        }

        return $this->req->getBaseUrl() . '/' . $this->append('', $argsOrParams);
    }
}
